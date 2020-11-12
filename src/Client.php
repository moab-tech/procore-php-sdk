<?php

namespace MoabTech\Procore;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use MoabTech\Procore\Api\Authentication;
use MoabTech\Procore\Api\Companies;
use MoabTech\Procore\Api\Me;
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
     * The current access token
     *
     * @var array
     */
    private $accessToken;

    /**
     * Client constructor.
     *
     * @param Builder
     *
     * @throws Exception
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
        $this->responseHistory = new History();

        $builder->addPlugin(new ExceptionThrower());
        $builder->addPlugin(new HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new HeaderDefaultsPlugin([
            'User-Agent' => self::USER_AGENT,
        ]));
        $builder->addPlugin(new RedirectPlugin());
    }

    /**
    * @return Authentication
    */
    public function authentication($clientId, $clientSecret)
    {
        return new Authentication($this, $clientId, $clientSecret);
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
    public function companies(?int $companyId = null)
    {
        return new Companies($this, $companyId);
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param string|array      $token
     *
     * @return $this
     */
    public function authenticate($accessToken)
    {
        $this->setAccessToken($accessToken);
        $this->getHttpClientBuilder()->removePlugin(AuthHeaders::class);
        $this->getHttpClientBuilder()->addPlugin(new AuthHeaders($this->accessToken['access_token']));

        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    private function setAccessToken(array $accessToken)
    {
        if (\is_array($accessToken)) {
            $this->accessToken = $accessToken;
        } else {
            $this->accessToken = [
                'access_token' => $accessToken,
            ];
        }
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
