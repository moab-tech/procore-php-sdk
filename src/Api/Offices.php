<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

use MoabTech\Procore\HttpClient\Util\UriBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Offices extends CompaniesAbstractApi implements ApiInterface
{
    public function list(array $params = [], array $headers = [])
    {
        $resolver = $this->createOptionsResolver();
        $resolver = $this->defineCompanyId($resolver);

        return $this->get('offices', $resolver->resolve($params), $headers);
    }

    public function create(array $params = [], array $headers = [])
    {
        if (array_key_exists('logo', $params)) {
            return $this->post('offices', $this->buildParams($params), $headers, ['logo' => $params['logo']]);
        }

        return $this->post('offices', $this->buildParams($params), $headers);
    }

    public function show(int $id, array $params = [], array $headers = [])
    {
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $uri = $this->buildUri((string) $id);

        return $this->get($uri, $resolver->resolve($params), $headers);
    }

    public function update(int $id, array $params = [], array $headers = [])
    {
        $uri = $this->buildUri((string) $id);

        if (array_key_exists('logo', $params)) {
            return $this->put($uri, $this->buildParams($params), $headers, ['logo' => $params['logo']]);
        }

        return $this->put($uri, $this->buildParams($params), $headers);
    }

    public function delete(int $id, array $params = [], array $headers = [])
    {
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $uri = $this->buildUri((string) $id);

        return $this->destroy($uri, $resolver->resolve($params), $headers);
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

    protected function buildParams($params)
    {
        return ['office' => $params, 'company_id' => $this->getClient()->getCompanyId()];
    }
}
