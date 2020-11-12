<?php

namespace MoabTech\Procore\Api;

use MoabTech\Procore\Client;
use MoabTech\Procore\Exception\MissingArgumentException;
use MoabTech\Procore\Exception\RuntimeException;
use MoabTech\Procore\HttpClient\Message\ResponseMediator;
use MoabTech\Procore\HttpClient\Util\JsonArray;
use Psr\Http\Message\ResponseInterface;

class Authentication extends AbstractApi
{
    /**
     * The client instance.
     *
     * @var Client
     */
    private $client;

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
        $this->client = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->getClient()->setUrl(static::AUTH_URL);
    }

    /**
     * Get the procore client instance.
     *
     * @return Client
     */
    protected function getClient()
    {
        return $this->client;
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

        return $this->post('oauth/token', $params);
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

        return $this->post('oauth/token', $payload);
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

        return $this->post('oauth/token', $payload);
    }

    /**
     * @param string               $uri
     * @param array<string,mixed>  $params
     * @param array<string,string> $headers
     *
     * @return mixed
     */
    protected function post(string $uri, array $params = [], array $headers = [])
    {
        $body = self::prepareJsonBody($params);

        if (null !== $body) {
            $headers = self::addJsonContentType($headers);
        }

        $response = $this->getClient()->getHttpClient()->post($uri, $headers, $body);

        return self::getContent($response);
    }

    /**
     * Prepare the request JSON body.
     *
     * @param array<string,mixed> $params
     *
     * @return string|null
     */
    private static function prepareJsonBody(array $params)
    {
        $params = \array_filter($params, function ($value): bool {
            return null !== $value;
        });

        if (0 === \count($params)) {
            return null;
        }

        return JsonArray::encode($params);
    }

    /**
     * Add the JSON content type to the headers if one is not already present.
     *
     * @param array<string,string> $headers
     *
     * @return array<string,string>
     */
    private static function addJsonContentType(array $headers)
    {
        return \array_merge(['Content-Type' => ResponseMediator::JSON_CONTENT_TYPE], $headers);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return stdClass
     */
    private static function getContent(ResponseInterface $response)
    {
        $content = ResponseMediator::getContent($response);

        if (null === $content) {
            throw new RuntimeException('No content was provided.');
        }

        return $content;
    }
}
