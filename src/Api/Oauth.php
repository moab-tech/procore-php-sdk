<?php

namespace MoabTech\Procore\Api;

use MoabTech\Procore\AccessToken;
use MoabTech\Procore\Client;
use MoabTech\Procore\Exception\MissingArgumentException;

class Oauth extends AbstractApi
{
    /**
     * The config.
     */
    private $config;

    /**
     * Authorization constructor.
     *
     * @param HttpClientInterface $client
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->config = $client->getConfig();
        $this->setPrefix('/');
    }

    public function getToken(array $headers = [])
    {
        $accessToken = null;
        if (! empty($this->config->getAccessToken())) {
            $accessToken = new AccessToken(
                $this->config->getAccessToken(),
                '',
                $this->config->getExpires(),
                $this->config->getRefreshToken()
            );
        } elseif (! empty($this->config->getRefreshToken())) {
            $accessToken = $this->refreshToken();
        } else {
            switch ($this->config->getGrantType()) {
                case 'authorization_code':
                    $accessToken = $this->getTokenByCode($headers);

                    break;
                case 'client_credentials':
                    $accessToken = $this->getTokenByClientCredentials($headers);

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
    public function getTokenByCode(array $headers = [])
    {
        if (empty($this->config->getCode())) {
            throw new MissingArgumentException('Missing code parameter.');
        }
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
            'code' => $this->config->getCode(),
            'redirect_uri' => $this->config->getRedirect(),
        ];

        $resBody = $this->post('oauth/token', $params, $headers);

        return new AccessToken($resBody['access_token'], $resBody['token_type'], self::setExpires($resBody['expires_in']), $resBody['refresh_token']);
    }

    /**
     * Returns an access token for the grant type "client_credentials".
     *
     * @return AccessToken
     */
    public function getTokenByClientCredentials(array $headers = [])
    {
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
        ];

        $resBody = $this->post('oauth/token', $params, $headers);

        return new AccessToken($resBody['access_token'], $resBody['token_type'], self::setExpires($resBody['expires_in']));
    }

    /**
     * Refresh access token.
     *
     * @param string $refreshToken
     *
     * @return AccessToken
     */
    public function refreshToken(array $headers = [])
    {
        if (empty($this->config->refreshToken)) {
            throw new MissingArgumentException('Missing refresh_token parameter.');
        }
        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
            'refresh_token' => $this->config->getRefreshToken(),
        ];

        $resBody = $this->post('oauth/token', $params, $headers);

        return new AccessToken($resBody['access_token'], $resBody['token_type'], self::setExpires($resBody['expires_in']), $resBody['refresh_token']);
    }

    protected static function setExpires(int $expires_in = 0)
    {
        return time() + $expires_in;
    }
}
