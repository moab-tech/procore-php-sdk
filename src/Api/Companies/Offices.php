<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Companies;

use MoabTech\Procore\HttpClient\Util\UriBuilder;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Offices extends AbstractCompaniesApi
{
    public function list(array $params = [], array $headers = [])
    {
        $resolver = $this->createOptionsResolver();
        $resolver = $this->defineCompanyId($resolver);

        return $this->get('offices', $resolver->resolve($params), $headers);
    }

    public function create(array $params = [], array $headers = [])
    {
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $resolver = $this->defineOfficeParams($resolver);
        if ($logo = $this->getArrayPath(['office', 'logo'], $params)) {
            return $this->post('offices', $resolver->resolve($params), $headers, ['logo' => $logo]);
        }

        return $this->post('offices', $resolver->resolve($params), $headers, []);
    }

    public function show(string $uri, array $params = [], array $headers = [])
    {
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $uri = $this->buildUri($uri);

        return $this->get($uri, $resolver->resolve($params), $headers);
    }

    public function update(string $uri, array $params = [], array $headers = [])
    {
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $resolver = $this->defineOfficeParams($resolver);
        $uri = $this->buildUri($uri);

        if ($logo = $this->getArrayPath(['office', 'logo'], $params)) {
            return $this->put($uri, $resolver->resolve($params), $headers, ['logo' => $logo]);
        }

        return $this->put($uri, $resolver->resolve($params), $headers, []);
    }

    public function delete(string $uri, array $params = [], array $headers = [])
    {
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $uri = $this->buildUri($uri);

        return $this->delete($uri, $resolver->resolve($params), $headers);
    }

    /**
     * Build the offices URI from the given parts.
     *
     * @param string ...$parts
     *
     * @return string
     */
    protected function buildUri(string ...$parts)
    {
        return UriBuilder::build('offices', ...$parts);
    }

    protected function defineOfficeParams($resolver)
    {
        $resolver->setDefined('office')
                    ->setRequired('office')
                    ->setAllowedTypes('office', 'array')
                    ->setAllowedValues('office', function (array $office) {
                        return 0 < \count($office);
                    })
                    ->setNormalizer('office', function (Options $resolver, array $office) {
                        $officeOptionsResolver = new OptionsResolver();
                        $officeOptionsResolver->setDefined('name')->setRequired('name')->setAllowedTypes('name', 'string');
                        $officeOptionsResolver->setDefined('address')->setAllowedTypes('address', 'string');
                        $officeOptionsResolver->setDefined('city')->setAllowedTypes('city', 'string');
                        $officeOptionsResolver->setDefined('state_code')->setAllowedTypes('state_code', 'string');
                        $officeOptionsResolver->setDefined('country_code')->setAllowedTypes('country_code', 'string');
                        $officeOptionsResolver->setDefined('zip')->setAllowedTypes('zip', 'string');
                        $officeOptionsResolver->setDefined('phone')->setAllowedTypes('phone', 'string');
                        $officeOptionsResolver->setDefined('fax')->setAllowedTypes('fax', 'string');
                        $officeOptionsResolver->setDefined('division')->setAllowedTypes('division', 'string');
                        $officeOptionsResolver->setDefined('logo')->setAllowedTypes('logo', 'string');

                        return \array_map(function ($office) use ($officeOptionsResolver) {
                            return $officeOptionsResolver->resolve($office);
                        }, $office);
                    });

        return $resolver;
    }
}
