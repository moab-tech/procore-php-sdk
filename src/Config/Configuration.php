<?php

namespace MoabTech\Procore\Config;

use MoabTech\Procore\Exception\ConfigurationException;

class Configuration implements ConfigurationInterface
{
    /**
     * The possible values for grant type.
     */
    const GRANT_TYPES = ['authorization_code', 'client_credentials'];

    /**
     * The client ID.
     *
     * @var string
     */
    protected $clientId;

    /**
     * The client secret.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * The authorization grant type.
     * Allowed values are: 'authorization_code'|'client_credentials'.
     *
     * @var string
     */
    protected $grantType;

    /**
     * The authorization code.
     *
     * @var string
     */
    protected $code;

    /**
     * The access token.
     *
     * @var null|string
     */
    protected $accessToken;

    /**
     * The refresh token.
     *
     * @var null|string
     */
    protected $refreshToken;

    /**
     * Custom headers to be sent with every request.
     *
     * @var array
     */
    protected $customHeaders;

    /**
     * Create a new configuration.
     *
     * @param array $config
     *
     * @throws ConfigurationException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'client_id' => null,
            'client_secret' => null,
            'grant_type' => 'authorization_code',
            'code' => null,
            'access_token' => null,
            'refresh_token' => null,
            'custom_headers' => [],
        ], $config);

        $this->setClientId($config['client_id']);
        $this->setClientSecret($config['client_secret']);
        $this->setGrantType($config['grant_type']);
        $this->setCode($config['code']);
        $this->setCustomHeaders($config['custom_headers']);
        $this->accessToken = $config['access_token'];
        $this->refreshToken = $config['refresh_token'];
    }

    /**
     * Returns the client ID.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Returns the client secret.
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Returns authorization code.
     *
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns grant type.
     *
     * @return string;
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * Returns initial access token.
     *
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Returns initial refresh token.
     *
     * @return null|string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Returns associative array with custom headers.
     *
     * @return array
     */
    public function getCustomHeaders()
    {
        return $this->customHeaders;
    }

    /**
     * Set client ID.
     *
     * @param string $clientId
     *
     * @throws ConfigurationException
     */
    protected function setClientId($clientId)
    {
        if (empty($clientId)) {
            throw new ConfigurationException('Missing mandatory parameter client_id');
        }
        $this->clientId = $clientId;
    }

    /**
     * Set client secret.
     *
     * @param string $clientSecret
     *
     * @throws ConfigurationException
     */
    protected function setClientSecret($clientSecret)
    {
        if (empty($clientSecret)) {
            throw new ConfigurationException('Missing mandatory parameter client_secret');
        }
        $this->clientSecret = $clientSecret;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @throws ConfigurationException
     */
    protected function setCode($code)
    {
        if (empty($code) && $this->getGrantType() === 'authorization_code') {
            throw new ConfigurationException('Missing mandatory parameter code');
        }
        $this->code = $code;
    }

    /**
     * Set the authorization grant type.
     *
     * @param array $grantType
     *
     * @throws ConfigurationException
     */
    protected function setGrantType($grantType)
    {
        if (! in_array($grantType, $this::GRANT_TYPES)) {
            throw new ConfigurationException('Invalid parameter for "grant_type". Allowed values are: ' . implode(" | ", $this::GRANT_TYPES) . '.');
        }
        $this->grantType = $grantType;
    }

    /**
     * Set the associative array with custom headers.
     *
     * @param array $customHeaders
     */
    public function setCustomHeaders($customHeaders)
    {
        $this->customHeaders = is_array($customHeaders) ? $customHeaders : [];
    }
}
