<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Companies;

use MoabTech\Procore\HttpClient\Util\UriBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConstructionVolume extends AbstractCompaniesApi
{
    public function sendUrgentError(array $params = [])
    {
        $uri = $this->buildUri('urgent_error');
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $resolver->setDefined('message')->setAllowedTypes('message', 'string');

        return $this->post($uri, $resolver->resolve($params), [], []);
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
