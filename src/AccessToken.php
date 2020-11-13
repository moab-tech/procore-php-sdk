<?php

namespace MoabTech\Procore;

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
    public function __construct($value, $type = '', $expiresIn = 0, $refreshToken = null)
    {
        if ($value) {
            $this->value = $value;
        }
        if ($type) {
            $this->type = $type;
        }
        if ($expiresIn) {
            $this->expires = time() + $expiresIn;
        }

        if ($refreshToken) {
            $this->refreshToken = $refreshToken;
        }
    }

    /**
     * Returns true if expires is greater than current time
     *
     * @return bool
     */
    public function hasExpired()
    {
        return $this->getExpires() <= time();
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
}
