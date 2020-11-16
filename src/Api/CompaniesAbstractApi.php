<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

use MoabTech\Procore\Client;
use MoabTech\Procore\Exception\ConfigurationException;

abstract class CompaniesAbstractApi extends AbstractApi
{
    public function __construct(Client $client, int $perPage = null, int $page = null)
    {
        if (! $client->getCompanyId()) {
            throw new ConfigurationException('Please set a company for this endpoint.');
        }
        parent::__construct($client, $perPage, $page);
    }
}
