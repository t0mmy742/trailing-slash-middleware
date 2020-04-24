<?php

declare(strict_types=1);

namespace t0mmy742\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;
use t0mmy742\Middleware\TrailingSlashMiddleware;
use t0mmy742\MiddlewareDispatcher\MiddlewareDispatcher;

class TrailingSlashMiddlewareTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var MiddlewareDispatcher
     */
    private $middlewareDispatcher;

    /**
     * @var ObjectProphecy|ResponseFactoryInterface
     */
    private $responseFactoryProphecy;

    /**
     * @var ResponseInterface
     */
    private $response;

    private function createRequest(string $method, string $uri): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $uri);
    }

    protected function setUp(): void
    {
        $this->middlewareDispatcher = new MiddlewareDispatcher();
        $this->responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $this->response = new Response();

        $this->middlewareDispatcher->addMiddleware(
            new TrailingSlashMiddleware($this->responseFactoryProphecy->reveal())
        );
        $this->middlewareDispatcher->addCallable(
            function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                $stream = (new StreamFactory())->createStream((string) $request->getUri());
                return $this->response->withBody($stream);
            }
        );
    }

    public function testNoNeedTrim(): void
    {
        $request = $this->createRequest('GET', '/testWithoutTrailingSlash');

        $responseResult = $this->middlewareDispatcher->handle($request);

        $this->assertSame('/testWithoutTrailingSlash', (string) $responseResult->getBody());
    }

    public function testHomeNoTrim(): void
    {
        $request = $this->createRequest('GET', '/');

        $responseResult = $this->middlewareDispatcher->handle($request);

        $this->assertSame('/', (string) $responseResult->getBody());
    }

    public function testPostTrim(): void
    {
        $request = $this->createRequest('POST', '/test/');

        $responseResult = $this->middlewareDispatcher->handle($request);

        $this->assertSame('/test', (string) $responseResult->getBody());
    }

    public function testGetTrim(): void
    {
        $this->responseFactoryProphecy->createResponse(301)->shouldBeCalledOnce()->willReturn($this->response);
        $request = $this->createRequest('GET', '/test/');

        $responseResult = $this->middlewareDispatcher->handle($request);

        $this->assertSame('/test', (string) $responseResult->getHeader('Location')[0]);
    }
}
