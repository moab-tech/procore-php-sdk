<?php

declare(strict_types=1);

namespace MoabTech\Procore\Config;

/**
 * Interface ConfigurationInterface
 */
interface ConfigurationInterface
{
    /**
     * Returns client's ID.
     *
     * @return string
     */
    public function getClientId();

    /**
     * Returns client's secret.
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
}
