<?php

namespace MoabTech\Procore\HttpClient;

class Client
{
    /**
     * The private token authentication method.
     *
     * @var string
     */
    public const AUTH_HTTP_TOKEN = 'http_token';

    /**
     * The OAuth 2 token authentication method.
     *
     * @var string
     */
    public const AUTH_OAUTH_TOKEN = 'oauth_token';

    /**
     * The default base URL.
     *
     * @var string
     */
    private const BASE_URL = 'https://gitlab.com';

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
     * Instantiate a new Gitlab client.
     *
     * @param Builder|null $httpClientBuilder
     *
     * @return void
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
        $this->responseHistory = new History();

        $builder->addPlugin(new ProcoreExceptionThrower());
        $builder->addPlugin(new HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new RedirectPlugin());

        $this->setUrl(self::BASE_URL);
    }

    /**
     * Create a Gitlab\Client using an HTTP client.
     *
     * @param ClientInterface $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(ClientInterface $httpClient)
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param string      $token      Gitlab private token
     * @param string      $authMethod One of the AUTH_* class constants
     * @param string|null $sudo
     *
     * @return $this
     */
    public function authenticate(string $token, string $authMethod, string $sudo = null)
    {
        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($authMethod, $token, $sudo));

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
