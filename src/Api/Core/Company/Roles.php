<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Core\Company;

use MoabTech\Procore\Api\CompaniesAbstractApi;
use MoabTech\Procore\HttpClient\Util\UriBuilder;

class Roles extends CompaniesAbstractApi
{
    public function list(array $headers = [])
    {
        $uri = $this->buildUri();

        return $this->get($uri, [], $headers);
    }

    /**
     * Build the URI
     *
     * @param string ...$parts
     *
     * @return string
     */
    protected function buildUri()
    {
        return UriBuilder::build('companies', (string) $this->getClient()->getCompanyId(), 'roles');
    }
}
