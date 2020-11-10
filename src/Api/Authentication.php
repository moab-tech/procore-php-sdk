<?php

namespace MoabTech\Procore\Auth;

use MoabTech\Procore\Api\AbstractApi;
use MoabTech\Procore\Client;
use MoabTech\Procore\Config\ConfigurationInterface;
use MoabTech\Procore\Exception\MissingArgumentException;

class Authentication extends AbstractApi
{
    /**
     * The client for the http communication.
     */
    protected $client;

    /**
     * The application's configuration.
     */
    protected $config;

    /**
     * The default auth URL.
     *
     * @var string
     */
    private const AUTH_URL = 'https://login.procore.com';

    /**
     * Authorization constructor.
     *
     * @param SumUpHttpClientInterface $client
     * @param ApplicationConfigurationInterface $config
     */
    public function __construct(Client $client, ConfigurationInterface $config)
    {
        $this->client = $client;
        $this->config = $config;
        $this->client->setUrl(static::AUTH_URL);
    }

    /**
     * Returns an access token according to the grant_type.
     *
     * @return null|AccessToken
     */
    public function getToken()
    {
        $accessToken = null;
        if (! empty($this->config->getAccessToken())) {
            $accessToken = new AccessToken(
                $this->config->getAccessToken(),
                '',
                0,
                $this->config->getRefreshToken()
            );
        } elseif (! empty($this->config->getRefreshToken())) {
            $accessToken = new AccessToken(
                '',
                '',
                0,
                $this->config->getRefreshToken()
            );
        } else {
            switch ($this->config->getGrantType()) {
                case 'authorization_code':
                    $accessToken = $this->getTokenByCode();

                    break;
                case 'client_credentials':
                    $accessToken = $this->getTokenByClientCredentials();

                    break;
            }
        }

        return $accessToken;
    }

    /**
     * Returns an access token for the grant type "authorization_code".
     *
     * @return AccessToken
     */
    public function getTokenByCode()
    {
        $payload = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
            'code' => $this->config->getCode(),
        ];
        $response = $this->post('/oauth/token', $payload);

        return new AccessToken($response->access_token, $response->token_type, $response->expires_in, $response->refresh_token);
    }

    /**
     * Returns an access token for the grant type "client_credentials".
     *
     * @return AccessToken
     */
    public function getTokenByClientCredentials()
    {
        $payload = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
        ];
        $response = $this->post('/oauth/token', $payload);

        return new AccessToken($response->access_token, $response->token_type, $response->expires_in);
    }

    /**
     * Refresh access token.
     *
     * @param string $refreshToken
     *
     * @return AccessToken
     */
    public function refreshToken($refreshToken)
    {
        if (empty($refreshToken)) {
            throw new MissingArgumentException('Missing refresh_token parameter.');
        }
        $payload = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
            'refresh_token' => $refreshToken,
        ];
        $response = $this->post('/oauth/token', $payload);

        return new AccessToken($response->access_token, $response->token_type, $response->expires_in, $response->refresh_token);
    }
}
