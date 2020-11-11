<?php

declare(strict_types=1);

namespace MoabTech\Procore\HttpClient\Util;

use ValueError;

/**
 * The is the URI builder helper class.
 *
 * @internal
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class UriBuilder
{
    /**
     * Build a URI from the given parts.
     *
     * @param string ...$parts
     *
     * @return string
     */
    public static function build(string ...$parts)
    {
        foreach ($parts as $index => $part) {
            if ('' === $part) {
                throw new ValueError(\sprintf('%s::buildUri(): Argument #%d ($parts) must non-empty', self::class, $index + 1));
            }

            $parts[$index] = \rawurlencode($part);
        }

        return \implode('/', $parts);
    }

    /**
     * Append a URI separator to the given URI.
     *
     * @param string $uri
     *
     * @return string
     */
    public static function appendSeparator(string $uri)
    {
        return \sprintf('%s%s', $uri, '/');
    }
}
