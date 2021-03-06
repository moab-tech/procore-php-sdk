<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Core\Company;

use MoabTech\Procore\Api\CompaniesAbstractApi;
use MoabTech\Procore\HttpClient\Util\UriBuilder;

class ConstructionVolume extends CompaniesAbstractApi
{
    public function sendUrgentError(array $params = [])
    {
        $uri = $this->buildUri('urgent_error');

        return $this->post($uri, $params);
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
        return UriBuilder::build('companies', (string) $this->getClient()->getCompanyId(), 'construction_volume', ...$parts);
    }
}
