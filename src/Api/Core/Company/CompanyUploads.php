<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Core\Company;

use MoabTech\Procore\Api\CompaniesAbstractApi;
use MoabTech\Procore\HttpClient\Util\UriBuilder;

class CompanyUploads extends CompaniesAbstractApi
{
    public function create(array $params = [], array $headers = [])
    {
        $uri = $this->buildUri();

        return $this->post($uri, $params, $headers);
    }

    /**
     * Build the uploads URI from the given parts.
     *
     * @param string ...$parts
     *
     * @return string
     */
    protected function buildUri(string ...$parts)
    {
        return UriBuilder::build('companies', (string) $this->getClient()->getCompanyId(), 'uploads', ...$parts);
    }
}
