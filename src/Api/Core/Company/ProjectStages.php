<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Core\Company;

use MoabTech\Procore\Api\ApiInterface;
use MoabTech\Procore\Api\CompaniesAbstractApi;
use MoabTech\Procore\HttpClient\Util\UriBuilder;

class ProjectStages extends CompaniesAbstractApi implements ApiInterface
{
    public function list(array $params = [], array $headers = [])
    {
        $resolver = $this->createOptionsResolver();
        $resolver->setDefined('project_id')->setAllowedTypes('project_id', 'int');
        if (null !== $this->getClient()->getProjectId() && ! array_key_exists('project_id', $params)) {
            array_merge(['project_id' => $this->getClient()->getProjectId()], $params);
        }
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

    public function update(int $id, array $params = [], array $headers = [])
    {
        $uri = $this->buildUri((string) $id);

        return $this->put($uri, $this->buildParams($params), $headers);
    }

    public function delete(int $id, array $headers = [])
    {
        $uri = $this->buildUri((string) $id);

        return $this->destroy($uri, [], $headers);
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
        return UriBuilder::build('companies', (string) $this->getClient()->getCompanyId(), 'project_stages', ...$parts);
    }

    protected function buildParams($params)
    {
        return ['project_stage' => $params];
    }
}
