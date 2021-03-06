<?php

namespace MoabTech\Procore;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use MoabTech\Procore\Api\Core\CoreResources;
use MoabTech\Procore\Api\Me;
use MoabTech\Procore\Api\Oauth;
use MoabTech\Procore\Config\Configuration;
use MoabTech\Procore\Exception\ConfigurationException;
use MoabTech\Procore\HttpClient\Builder;
use MoabTech\Procore\HttpClient\Plugin\AuthHeaders;
use MoabTech\Procore\HttpClient\Plugin\ExceptionThrower;
use MoabTech\Procore\HttpClient\Plugin\History;
use MoabTech\Procore\HttpClient\Plugin\ProcoreHeaders;

class Client
{
    use CoreResources;

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
     * @var string
     */
    private $accessToken;

    /**
     * The current company
     *
     * @var int
     */
    private $companyId;

    /**
     * The current project
     *
     * @var int
     */
    private $projectId;

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
        $this->getHttpClientBuilder()->addPlugin(new AuthHeaders($this->getAccessToken()));

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
     * Set a project id for all next requests.
     *
     * @param int      $projectId
     *
     * @return $this
     */
    public function forProject(?int $projectId = null)
    {
        if ($projectId) {
            $this->projectId = $projectId;
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
     * @return string
     *
     * @throws ConfigurationException
     */
    public function refreshToken($refreshToken = null)
    {
        if (! isset($refreshToken)) {
            throw new ConfigurationException('There is no refresh token');
        }

        $this->accessToken = $this->oauth->refreshToken($refreshToken);

        return $this->accessToken;
    }

    /**
     * Get the access token.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function getProjectId()
    {
        return $this->projectId;
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
