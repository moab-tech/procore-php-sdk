<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Companies;

use MoabTech\Procore\HttpClient\Util\UriBuilder;

class ConstructionVolume extends AbstractCompaniesApi
{
    public function sendUrgentError(array $params = [])
    {
        $uri = $this->buildUri('urgent_error');

        return $this->post($uri, $params, [], []);
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
        return UriBuilder::build('companies', (string) $this->companyId, 'construction_volume', ...$parts);
    }
}
