<?php

namespace MoabTech\Procore\Auth;

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
     * The number of seconds the access token will be valid.
     *
     * @var int
     */
    protected $expiresIn;

    /**
     * The refresh token.
     *
     * @var string
     */
    protected $refreshToken;

    /**
     * @var int
     */
    private static $timeNow;

    /**
     * Set the time now. This should only be used for testing purposes.
     *
     * @param int $timeNow the time in seconds since epoch
     * @return void
     */
    public static function setTimeNow($timeNow)
    {
        self::$timeNow = $timeNow;
    }

    /**
     * Reset the time now if it was set for test purposes.
     *
     * @return void
     */
    public static function resetTimeNow()
    {
        self::$timeNow = null;
    }

    /**
     * @return int
     */
    public function getTimeNow()
    {
        return self::$timeNow ? self::$timeNow : time();
    }

    /**
     * Create a new access token entity.
     *
     * @param string $value
     * @param string $type
     * @param int    $expiresIn
     * @param array  $scope
     * @param string $refreshToken
     */
    public function __construct($value, $type = '', $expiresIn = -1, $refreshToken = null)
    {
        if ($value) {
            $this->value = $value;
        }
        if ($type) {
            $this->type = $type;
        }
        if ($expiresIn) {
            $this->expires += $this->getTimeNow();
        }
        if ($refreshToken) {
            $this->refreshToken = $refreshToken;
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
     * Returns the timestampe when the token will expire
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
