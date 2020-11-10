<?php

declare(strict_types=1);

namespace MoabTech\Procore\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use MoabTech\Procore\Exception\ApiLimitExceededException;
use MoabTech\Procore\Exception\AuthenticationException;
use MoabTech\Procore\Exception\ErrorException;
use MoabTech\Procore\Exception\RuntimeException;
use MoabTech\Procore\Exception\ValidationFailedException;
use MoabTech\Procore\HttpClient\Message\ResponseMediator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A plugin to remember the last response.
 *
 * @internal
 */
final class ExceptionThrower implements Plugin
{
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
        return $next($request)->then(function (ResponseInterface $response) {
            $status = $response->getStatusCode();

            if ($status >= 400 && $status < 600) {
                throw self::createException($status, ResponseMediator::getErrorMessage($response) ?? $response->getReasonPhrase());
            }

            return $response;
        });
    }

    /**
     * Create an exception from a status code and error message.
     *
     * @param int    $status
     * @param string $message
     *
     * @return ErrorException|RuntimeException
     */
    private static function createException(int $status, string $message)
    {
        if (400 === $status || 422 === $status) {
            return new ValidationFailedException($message, $status);
        }

        if (429 === $status) {
            return new ApiLimitExceededException($message, $status);
        }

        if (401 === $status || 403 === $status) {
            return new AuthenticationException($message, $status);
        }

        return new RuntimeException($message, $status);
    }
}
