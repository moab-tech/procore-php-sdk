<?php

namespace MoabTech\Procore;

use MoabTech\Procore\Exception\MissingArgumentException;

/**
 * Class AccessToken
 *
 * @package SumUp\Authentication
 */
class AccessToken
{
    /**
     * The access token value.
     *
     * @var string
     */
    protected $value = '';

    /**
     * The access token type.
     *
     * @var string
     */
    protected $type = '';

    /**
     * The expirationg date in seconds
     *
     * @var int
     */
    protected $expires;

    /**
     * The refresh token.
     *
     * @var string
     */
    protected $refreshToken;

    /**
     * Create a new access token entity.
     *
     * @param array $token
     */
    public function __construct(array $token = [])
    {
        if (! array_key_exists('access_token', $token)) {
            throw new MissingArgumentException('An access token is required');
        } else {
            $this->value = $token['access_token'];
            $this->refreshToken = array_key_exists('refresh_token', $token) ? $token['refresh_token'] : null;
            $this->type = array_key_exists('type', $token) ? $token['type'] : null;
            $this->setExpires($token);
        }
    }

    /**
     * Returns the access token.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the type of the access token.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the expiration date in seconds.
     *
     * @return int
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Returns the refresh token if any.
     *
     * @return null|string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function setExpires(array $token)
    {
        if (! array_key_exists('expires_in', $token)) {
            $this->expires = 0;
        }

        $this->expires = time() + $token['expires_in'];
    }
}
