<?php

namespace MoabTech\Procore;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use MoabTech\Procore\Api\Companies;
use MoabTech\Procore\Api\ConstructionVolume;
use MoabTech\Procore\Api\Me;
use MoabTech\Procore\Api\Oauth;
use MoabTech\Procore\Api\Offices;
use MoabTech\Procore\Api\Uploads;
use MoabTech\Procore\Config\Configuration;
use MoabTech\Procore\Exception\ConfigurationException;
use MoabTech\Procore\HttpClient\Builder;
use MoabTech\Procore\HttpClient\Plugin\AuthHeaders;
use MoabTech\Procore\HttpClient\Plugin\ExceptionThrower;
use MoabTech\Procore\HttpClient\Plugin\History;
use MoabTech\Procore\HttpClient\Plugin\ProcoreHeaders;

class Client
{
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
     * The current configuration
     *
     * @var Configuration
     */
    private $config;

    /**
     * The current access token
     *
     * @var AccessToken
     */
    private $accessToken;

    /**
     * The current company
     *
     * @var int
     */
    private $companyId;

    /**
     * Client constructor.
     *
     * @param Builder
     *
     * @throws Exception
     */
    public function __construct(array $config = [], Builder $httpClientBuilder = null)
    {
        $this->setConfig($config);
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
        $this->responseHistory = new History();

        $builder->addPlugin(new ExceptionThrower());
        $builder->addPlugin(new HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new HeaderDefaultsPlugin([
            'User-Agent' => self::USER_AGENT,
        ]));
        $builder->addPlugin(new RedirectPlugin());

        $this->authenticate();
    }

    /**
     * @return Me
     */
    public function me()
    {
        return new Me($this);
    }

    /**
     * @return Companies
     */
    public function companies()
    {
        return new Companies($this);
    }

    /**
     * @return Offices
     */
    public function offices()
    {
        if (! $this->getCompanyId()) {
            throw new ConfigurationException('Please set a company for this endpoint.');
        }

        return new Offices($this);
    }

    /**
     * @return Uploads
     */
    public function uploads()
    {
        if (! $this->getCompanyId()) {
            throw new ConfigurationException('Please set a company for this endpoint.');
        }

        return new Uploads($this);
    }

    /**
     * @return ConstructionVolume
     */
    public function constructionVolume()
    {
        if (! $this->getCompanyId()) {
            throw new ConfigurationException('Please set a company for this endpoint.');
        }

        return new ConstructionVolume($this);
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @return $this
     */
    public function authenticate()
    {
        $this->setUrl($this->config->getAuthUrl());

        $authService = new Oauth($this);
        $this->accessToken = $authService->getToken();

        $this->getHttpClientBuilder()->removePlugin(AuthHeaders::class);
        $this->getHttpClientBuilder()->addPlugin(new AuthHeaders($this->getAccessToken()->getValue()));

        $this->setUrl($this->config->getBaseUrl());

        return $this;
    }

    /**
     * Set a company id for all next requests.
     *
     * @param int      $companyId
     *
     * @return $this
     */
    public function forCompany(?int $companyId = null)
    {
        $this->getHttpClientBuilder()->removePlugin(ProcoreHeaders::class);

        if ($companyId) {
            $this->getHttpClientBuilder()->addPlugin(new ProcoreHeaders($companyId));
            $this->companyId = $companyId;
        }

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
     * Sets the config array; useful if changing config after client is already newed up
     *
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config = [])
    {
        $this->config = new Configuration($config);

        return $this;
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
        $this->accessToken = $this->oauth->refreshToken($rToken);

        return $this->accessToken;
    }

    /**
     * Get the access token.
     *
     * @return AccessToken|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Get the current configuration
     *
     * @return Configuration|null
     */
    public function getConfig()
    {
        return $this->config;
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
