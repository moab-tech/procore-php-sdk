<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Core\Company;

use MoabTech\Procore\Api\ApiInterface;
use MoabTech\Procore\Api\CompaniesAbstractApi;
use MoabTech\Procore\HttpClient\Util\UriBuilder;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Trades extends CompaniesAbstractApi implements ApiInterface
{
    public function list(array $params = [], array $headers = [])
    {
        $resolver = $this->createOptionsResolver();
        $resolver->setDefined('filters')
        ->setDefined('filters')
        ->setAllowedTypes('filters', 'array')
        ->setNormalizer('filters', function (Options $resolver, array $filters) {
            $filtersOptionsResolver = new OptionsResolver();
            $filtersOptionsResolver->setDefined('updated_at')
                            ->setAllowedTypes('updated_at', 'string');
            $filtersOptionsResolver->setDefined('active')
                            ->setAllowedTypes('active', 'boolean');

            return $filtersOptionsResolver->resolve($filters);
        });
        $uri = $this->buildUri();

        return $this->get($uri, $resolver->resolve($params), $headers);
    }

    public function create(array $params = [], array $headers = [])
    {
        $uri = $this->buildUri();

        return $this->post($uri, $this->buildParams($params), $headers);
    }

    public function show(int $id, array $headers = [])
    {
        $uri = $this->buildUri((string) $id);

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
        return UriBuilder::build('companies', (string) $this->getClient()->getCompanyId(), 'trades');
    }

    protected function buildParams($params)
    {
        return ['trade' => $params];
    }
}
