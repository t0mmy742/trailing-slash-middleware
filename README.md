# Trailing Slash Middleware (PSR-15)

[![Build Status](https://travis-ci.org/t0mmy742/trailing-slash-middleware.svg?branch=master)](https://travis-ci.org/t0mmy742/trailing-slash-middleware)
[![Coverage Status](https://coveralls.io/repos/github/t0mmy742/trailing-slash-middleware/badge.svg?branch=master)](https://coveralls.io/github/t0mmy742/trailing-slash-middleware?branch=master)

This middleware remove trailing slash from URI.
It implements PSR-15 MiddlewareInterface and need a PSR-17 ResponseFactory to work.

## Installation

```bash
$ composer require t0mmy742/trailing-slash-middleware
```

## Usage

```php
<?php

use t0mmy742\Middleware\TrailingSlashMiddleware;

$responseFactory = new \Your\PSR17\ResponseFactory();
$middleware = new TrailingSlashMiddleware($responseFactory);
```

If path does not contain trailing slash, or if it is home ('/'), it does nothing.
Otherwise, if it is a GET request, it creates a new Response with a 301 Permanent Redirect to the new URI (if not post, just handle request with new URI).