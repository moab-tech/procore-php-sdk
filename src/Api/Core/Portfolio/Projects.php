<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Core\Portfolio;

use MoabTech\Procore\Api\ApiInterface;
use MoabTech\Procore\Api\CompaniesAbstractApi;
use MoabTech\Procore\HttpClient\Util\UriBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Projects extends CompaniesAbstractApi implements ApiInterface
{
    public function list(array $params = [], array $headers = [])
    {
        $resolver = $this->createOptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
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
        $resolver = new OptionsResolver();
        $resolver = $this->defineCompanyId($resolver);
        $uri = $this->buildUri((string) $id);

        return $this->get($uri, $resolver->resolve([]), $headers);
    }

    public function update(int $id, array $params = [], array $headers = [])
    {
        $uri = $this->buildUri((string) $id);

        return $this->put($uri, $this->buildParams($params), $headers);
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
        return UriBuilder::build('projects', ...$parts);
    }

    protected function buildParams($params)
    {
        return ['project' => $params];
    }
}
