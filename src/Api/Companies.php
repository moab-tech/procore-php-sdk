<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

use MoabTech\Procore\Api\Companies\ConstructionVolume;
use MoabTech\Procore\Api\Companies\Offices;
use MoabTech\Procore\Api\Companies\Uploads;
use MoabTech\Procore\Client;

class Companies extends AbstractApi
{
    /**
     * The URI prefix.
     *
     * @var string
     */
    protected const URI_PREFIX = '/vapid/';

    /**
     * The company.
     *
     * @var string|null
     */
    protected $companyId;

    /**
     * Create a new API instance.
     *
     * @param Client   $client
     * @param string|null   $companyId
     * @param int|null $perPage
     *
     * @return void
     */
    public function __construct(Client $client, ?string $companyId = null, int $perPage = null, int $page = null)
    {
        parent::__construct($client, $perPage, $page);
        $this->companyId = $companyId;
    }

    public function list(array $params = [])
    {
        $resolver = $this->createOptionsResolver();

        return $this->get('companies', $resolver->resolve($params));
    }

    public function offices()
    {
        return new Offices($this->getClient(), $this->companyId, $this->getPerPage(), $this->getPage());
    }

    public function uploads()
    {
        return new Uploads($this->getClient(), $this->companyId);
    }

    public function constructionVolume()
    {
        return new ConstructionVolume($this->getClient(), $this->companyId);
    }
}
