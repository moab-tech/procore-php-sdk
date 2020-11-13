<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Companies;

use MoabTech\Procore\HttpClient\Util\UriBuilder;
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
        if ($logo = $this->getArrayPath(['office', 'logo'], $params)) {
            return $this->post('offices', $params, $headers, ['logo' => $logo]);
        }

        return $this->post('offices', $params, $headers, []);
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
        $uri = $this->buildUri($uri);

        if ($logo = $this->getArrayPath(['office', 'logo'], $params)) {
            return $this->put($uri, $params, $headers, ['logo' => $logo]);
        }

        return $this->put($uri, $params, $headers, []);
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
}
