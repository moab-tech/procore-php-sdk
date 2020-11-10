<?php

namespace MoabTech\Procore\Config;

/**
 * Interface ConfigurationInterface
 */
interface ConfigurationInterface
{
    /**
     * Returns the client ID.
     *
     * @return string
     */
    public function getClientId();

    /**
     * Returns the client secret.
     *
     * @return string
     */
    public function getClientSecret();

    /**
     * Returns authorization code.
     *
     * @return string
     */
    public function getCode();

    /**
     * Returns grant type.
     *
     * @return string
     */
    public function getGrantType();

    /**
     * Returns access token.
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Returns refresh token.
     *
     * @return string
     */
    public function getRefreshToken();

    /**
     * Returns associative array with custom headers.
     *
     * @return array
     */
    public function getCustomHeaders();
}
