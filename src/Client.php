<?php

namespace MoabTech\Procore;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use MoabTech\Procore\Api\ApiResources;
use MoabTech\Procore\Auth\Authentication;
use MoabTech\Procore\Config\Configuration;
use MoabTech\Procore\Exception\ConfigurationException;
use MoabTech\Procore\HttpClient\Builder;
use MoabTech\Procore\HttpClient\Plugin\AuthHeaders;
use MoabTech\Procore\HttpClient\Plugin\ExceptionThrower;
use MoabTech\Procore\HttpClient\Plugin\History;
use MoabTech\Procore\HttpClient\Plugin\ProcoreHeaders;

class Client
{
    use ApiResources;

    /**
     * The configuration.
     *
     * @var Configuration
     */
    protected $config;

    /**
     * The access token that holds the data from the response.
     *
     * @var Auth\AccessToken
     */
    protected $accessToken;

    /**
     * The default base URL.
     *
     * @var string
     */
    private const BASE_URL = 'https://api.procore.com';

    /**
     * The default user agent header.
     *
     * @var string
     */
    private const USER_AGENT = 'procore-php-sdk/1.0';

    /**
     * The HTTP client builder.
     *
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * The response history plugin.
     *
     * @var History
     */
    private $responseHistory;

    /**
     * Client constructor.
     *
     * @param array $config
     * @param Builder
     *
     * @throws Exception
     */
    public function __construct(array $config = [], Builder $httpClientBuilder = null)
    {
        $this->config = new Configuration($config);
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
        $this->responseHistory = new History();

        $builder->addPlugin(new ExceptionThrower());
        $builder->addPlugin(new HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new HeaderDefaultsPlugin([
            'User-Agent' => self::USER_AGENT,
        ]));
        $builder->addPlugin(new RedirectPlugin());

        $authentication = new Authentication($this->client, $this->appConfig);
        $this->accessToken = $authentication->getToken();
        $this->authenticate($this->accessToken->getValue());

        $this->setUrl(self::BASE_URL);
    }

    /**
     * Returns the access token.
     *
     * @return Authentication\AccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Refresh the access token.
     *
     * @param string $refreshToken
     *
     * @return Authentication\AccessToken
     *
     * @throws ConfigurationException
     */
    public function refreshToken($refreshToken = null)
    {
        if (isset($refreshToken)) {
            $rToken = $refreshToken;
        } elseif (! isset($refreshToken) && ! isset($this->accessToken)) {
            throw new ConfigurationException('There is no refresh token');
        } else {
            $rToken = $this->accessToken->getRefreshToken();
        }
        $authentication = new Authentication($this->client, $this->appConfig);
        $this->accessToken = $authentication->refreshToken($rToken);

        return $this->accessToken;
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param string      $token
     *
     * @return $this
     */
    public function authenticate(string $token)
    {
        $this->getHttpClientBuilder()->removePlugin(AuthHeaders::class);
        $this->getHttpClientBuilder()->addPlugin(new AuthHeaders($token));

        return $this;
    }

    /**
     * Set a company id for all next requests.
     *
     * @param int      $companyId
     *
     * @return $this
     */
    public function forCompany(int $companyId)
    {
        if ($this->config->getGrantType() !== 'client_credentials') {
            $this->getHttpClientBuilder()->removePlugin(ProcoreHeaders::class);
            $this->getHttpClientBuilder()->addPlugin(new ProcoreHeaders($companyId));
        }

        $this->companyId = $companyId;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url)
    {
        $uri = $this->getHttpClientBuilder()->getUriFactory()->createUri($url);

        $this->getHttpClientBuilder()->removePlugin(AddHostPlugin::class);
        $this->getHttpClientBuilder()->addPlugin(new AddHostPlugin($uri));

        return $this;
    }

    /**
     * Get the last response.
     *
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * Get the HTTP client.
     *
     * @return HttpMethodsClientInterface
     */
    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * Get the stream factory.
     *
     * @return StreamFactoryInterface
     */
    public function getStreamFactory()
    {
        return $this->getHttpClientBuilder()->getStreamFactory();
    }

    /**
     * Get the HTTP client builder.
     *
     * @return Builder
     */
    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }
}
