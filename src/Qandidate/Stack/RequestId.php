<?php

/*
 * This file is part of the qandidate/stack-request-id package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Stack;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Middleware adding a unique request id to the request if it is not present.
 */
class RequestId implements HttpKernelInterface
{
    private $app;
    private $generator;
    private $header;

    public function __construct(HttpKernelInterface $app, RequestIdGenerator $generator, $header = 'X-Request-Id')
    {
        $this->app       = $app;
        $this->generator = $generator;
        $this->header    = $header;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        if ( ! $request->headers->has($this->header)) {
            $request->headers->set($this->header, $this->generator->generate());
        }

        return $this->app->handle($request, $type, $catch);
    }
}
