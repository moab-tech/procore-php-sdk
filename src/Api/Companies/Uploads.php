<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Companies;

use MoabTech\Procore\HttpClient\Util\UriBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Uploads extends AbstractCompaniesApi
{
    public function create(array $params = [], array $headers = [])
    {
        $uri = $this->buildUri();
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $resolver->setDefined('response_filename')->setAllowedTypes('response_filename', 'string');
        $resolver->setDefined('response_content_type')->setAllowedTypes('response_content_type', 'string');

        return $this->post($uri, $resolver->resolve($params), $headers);
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
        return UriBuilder::build('companies', (string) $this->companyId, 'uploads', ...$parts);
    }
}
