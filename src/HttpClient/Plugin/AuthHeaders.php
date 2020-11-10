<?php

declare(strict_types=1);

namespace MoabTech\Procore\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

/**
 * Add authentication to the request.
 *
 * @internal
 */
final class AuthHeaders implements Plugin
{
    /**
     * @var array<string,string>
     */
    private $headers;

    /**
     * @param string      $token
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->headers = self::buildHeaders($token);
    }

    /**
     * Handle the request and return the response coming from the next callable.
     *
     * @param RequestInterface $request
     * @param callable         $next
     * @param callable         $first
     *
     * @return Promise
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        foreach ($this->headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        return $next($request);
    }

    /**
     * Build the headers to be attached to the request.
     *
     * @param string      $token
     *
     * @throws RuntimeException
     *
     * @return array<string,string>
     */
    private static function buildHeaders(string $token)
    {
        $headers = [];
        $headers['Authorization'] = \sprintf('Bearer %s', $token);

        return $headers;
    }
}
