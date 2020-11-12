<?php

namespace MoabTech\Procore\Api;

use MoabTech\Procore\Client;
use MoabTech\Procore\Exception\MissingArgumentException;

class Authentication extends AbstractApi
{
    /**
     * The clientId.
     */
    protected $clientId;

    /**
     * The clientSecret.
     */
    protected $clientSecret;

    /**
     * The default auth URL.
     *
     * @var string
     */
    private const AUTH_URL = 'https://login.procore.com';

    /**
     * Authorization constructor.
     *
     * @param HttpClientInterface $client
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(Client $client, $clientId, $clientSecret)
    {
        parent::__construct($client);
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->getClient()->setUrl(static::AUTH_URL);
    }

    /**
     * Returns an access token for the grant type "authorization_code".
     *
     * @return AccessToken
     */
    public function getTokenByCode($code)
    {
        if (empty($code)) {
            throw new MissingArgumentException('Missing code parameter.');
        }
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
        ];

        return $this->post('/oauth/token', $params);
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
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        return $this->post('/oauth/token', $payload);
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
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
        ];

        return $this->post('/oauth/token', $payload);
    }
}
