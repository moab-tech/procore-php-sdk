<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Companies;

use MoabTech\Procore\Api\AbstractApi;
use MoabTech\Procore\Client;

abstract class AbstractCompaniesApi extends AbstractApi
{
    /**
     * The companyId.
     *
     * @var string
     */
    protected $companyId;

    /**
     * Create a new API instance.
     *
     * @param Client   $client
     * @param string   $companyId
     * @param int|null $perPage
     *
     * @return void
     */
    public function __construct(Client $client, string $companyId, int $perPage = null)
    {
        parent::__construct($client, $perPage);
        $this->companyId = $companyId;
        $this->client->forCompany($this->companyId);
    }

    protected function defineCompanyId($resolver)
    {
        $resolver->setDefined('company_id')
            ->setDefault('company_id', $this->companyId)
            ->setAllowedTypes('company_id', 'int');

        return $resolver;
    }
}
