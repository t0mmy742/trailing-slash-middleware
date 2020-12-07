<?php

declare(strict_types=1);

namespace t0mmy742\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;
use t0mmy742\Middleware\TrailingSlashMiddleware;

class TrailingSlashMiddlewareTest extends TestCase
{
    public function testNoNeedTrim(): void
    {
        $path = '/testWithoutTrailingSlash';

        $responseFactory = $this->createStub(ResponseFactoryInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $requestHandler = $this->createMock(RequestHandlerInterface::class);

        $uri
            ->expects($this->exactly(2))
            ->method('getPath')
            ->willReturn($path);

        $serverRequest
            ->expects($this->exactly(2))
            ->method('getUri')
            ->willReturn($uri);

        $requestHandler
            ->expects($this->once())
            ->method('handle')
            ->with($serverRequest)
            ->willReturnCallback(function (ServerRequestInterface $request): ResponseInterface {
                $this->assertSame('/testWithoutTrailingSlash', $request->getUri()->getPath());

                return $this->createStub(ResponseInterface::class);
            });

        (new TrailingSlashMiddleware($responseFactory))->process($serverRequest, $requestHandler);
    }

    public function testHomeNoTrim(): void
    {
        $path = '/';

        $responseFactory = $this->createStub(ResponseFactoryInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $requestHandler = $this->createMock(RequestHandlerInterface::class);

        $uri
            ->expects($this->exactly(2))
            ->method('getPath')
            ->willReturn($path);

        $serverRequest
            ->expects($this->exactly(2))
            ->method('getUri')
            ->willReturn($uri);

        $requestHandler
            ->expects($this->once())
            ->method('handle')
            ->with($serverRequest)
            ->willReturnCallback(function (ServerRequestInterface $request): ResponseInterface {
                $this->assertSame('/', $request->getUri()->getPath());

                return $this->createStub(ResponseInterface::class);
            });

        (new TrailingSlashMiddleware($responseFactory))->process($serverRequest, $requestHandler);
    }

    public function testPostTrim(): void
    {
        $method = 'POST';
        $path = '/test/';

        $responseFactory = $this->createStub(ResponseFactoryInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri2 = $this->createMock(UriInterface::class);
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $requestHandler = $this->createMock(RequestHandlerInterface::class);

        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn($path);
        $uri
            ->expects($this->once())
            ->method('withPath')
            ->with('/test')
            ->willReturnCallback(function (string $path) use ($uri2): UriInterface {
                $uri2
                    ->expects($this->once())
                    ->method('getPath')
                    ->willReturn($path);
                return $uri2;
            });

        $serverRequest
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);
        $serverRequest
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);
        $serverRequest
            ->expects($this->once())
            ->method('withUri')
            ->willReturnCallback(function (UriInterface $uri): ServerRequestInterface {
                $serverRequest2 = $this->createMock(ServerRequestInterface::class);
                $serverRequest2
                    ->expects($this->once())
                    ->method('getUri')
                    ->willReturn($uri);

                return $serverRequest2;
            });

        $requestHandler
            ->expects($this->once())
            ->method('handle')
            ->with($serverRequest)
            ->willReturnCallback(function (ServerRequestInterface $request): ResponseInterface {
                $this->assertSame('/test', $request->getUri()->getPath());

                return $this->createStub(ResponseInterface::class);
            });

        (new TrailingSlashMiddleware($responseFactory))->process($serverRequest, $requestHandler);
    }

    public function testGetTrim(): void
    {
        $method = 'GET';
        $path = '/test/';

        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri2 = $this->createMock(UriInterface::class);
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $requestHandler = $this->createStub(RequestHandlerInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn($path);
        $uri
            ->expects($this->once())
            ->method('withPath')
            ->with('/test')
            ->willReturnCallback(function (string $path) use ($uri2): UriInterface {
                $uri2
                    ->expects($this->once())
                    ->method('__toString')
                    ->willReturn($path);
                return $uri2;
            });

        $serverRequest
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);
        $serverRequest
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $responseFactory
            ->expects($this->once())
            ->method('createResponse')
            ->with(301)
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('withHeader')
            ->with('Location', $this->isType('string'))
            ->willReturnCallback(function (string $name, $value) {
                $this->assertSame('/test', $value);

                return $this->createStub(ResponseInterface::class);
            });

        (new TrailingSlashMiddleware($responseFactory))->process($serverRequest, $requestHandler);
    }
}
