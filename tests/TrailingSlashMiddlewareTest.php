<?php

declare(strict_types=1);

namespace t0mmy742\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;
use t0mmy742\Middleware\TrailingSlashMiddleware;

class TrailingSlashMiddlewareTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy
     */
    private $requestProphecy;

    /**
     * @var ObjectProphecy
     */
    private $uriProphecy;

    /**
     * @var ObjectProphecy
     */
    private $responseFactoryProphecy;

    /**
     * @var ObjectProphecy
     */
    private $responseProphecy;

    /**
     * @var ObjectProphecy
     */
    private $handlerProphecy;

    /**
     * @var TrailingSlashMiddleware
     */
    private $middleware;

    protected function setUp(): void
    {
        $this->requestProphecy = $this->prophesize(ServerRequestInterface::class);

        $this->uriProphecy = $this->prophesize(UriInterface::class);
        $this->requestProphecy
            ->getUri()
            ->willReturn($this->uriProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);

        $this->responseProphecy = $this->prophesize(ResponseInterface::class);

        $this->handlerProphecy = $this->prophesize(RequestHandlerInterface::class);

        $this->middleware = new TrailingSlashMiddleware($this->responseFactoryProphecy->reveal());
    }

    public function testNoNeedTrim(): void
    {
        $this->uriProphecy
            ->getPath()
            ->willReturn('/testWithoutTrailingSlash')
            ->shouldBeCalledOnce();

        $this->handlerProphecy
            ->handle($this->requestProphecy->reveal())
            ->willReturn($this->responseProphecy->reveal())
            ->shouldBeCalledOnce();

        $response = $this->middleware->process($this->requestProphecy->reveal(), $this->handlerProphecy->reveal());
        $this->assertSame($this->responseProphecy->reveal(), $response);
    }

    public function testHomeNoTrim(): void
    {
        $this->uriProphecy
            ->getPath()
            ->willReturn('/')
            ->shouldBeCalledOnce();

        $this->handlerProphecy
            ->handle($this->requestProphecy->reveal())
            ->willReturn($this->responseProphecy->reveal())
            ->shouldBeCalledOnce();

        $response = $this->middleware->process($this->requestProphecy->reveal(), $this->handlerProphecy->reveal());
        $this->assertSame($this->responseProphecy->reveal(), $response);
    }

    public function testPostTrim(): void
    {
        $this->uriProphecy
            ->getPath()
            ->willReturn('/test/')
            ->shouldBeCalledOnce();

        $this->requestProphecy
            ->getMethod()
            ->willReturn('POST')
            ->shouldBeCalledOnce();

        $this->requestProphecy
            ->withUri($this->uriProphecy->reveal())
            ->willReturn($this->requestProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->uriProphecy
            ->withPath(Argument::type('string'))
            ->willReturn($this->uriProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->handlerProphecy
            ->handle($this->requestProphecy->reveal())
            ->willReturn($this->responseProphecy->reveal())
            ->shouldBeCalledOnce();

        $response = $this->middleware->process($this->requestProphecy->reveal(), $this->handlerProphecy->reveal());
        $this->assertSame($this->responseProphecy->reveal(), $response);
    }

    public function testGetTrim(): void
    {
        $this->uriProphecy
            ->getPath()
            ->willReturn('/test/')
            ->shouldBeCalledOnce();

        $this->requestProphecy
            ->getMethod()
            ->willReturn('GET')
            ->shouldBeCalledOnce();

        $this->uriProphecy
            ->withPath(Argument::type('string'))
            ->willReturn($this->uriProphecy->reveal())
            ->shouldBeCalledOnce();
        $this->uriProphecy
            ->__toString()
            ->willReturn('http://example.com/test')
            ->shouldBeCalledOnce();

        $this->responseFactoryProphecy
            ->createResponse(Argument::type('int'))
            ->willReturn($this->responseProphecy->reveal())
            ->shouldBeCalledOnce();
        $this->responseProphecy
            ->withHeader(Argument::type('string'), Argument::type('string'))
            ->willReturn($this->responseProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->handlerProphecy
            ->handle($this->requestProphecy->reveal())
            ->willReturn($this->prophesize(ResponseInterface::class)->reveal())
            ->shouldNotBeCalled();

        $response = $this->middleware->process($this->requestProphecy->reveal(), $this->handlerProphecy->reveal());
        $this->assertSame($this->responseProphecy->reveal(), $response);
    }
}
