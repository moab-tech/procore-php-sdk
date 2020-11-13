<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

class Me extends AbstractApi
{
    public function show(array $params = [], array $headers = [])
    {
        if (! $this->getClient()->getCompanyId()) {
            return $this->get('me');
        }

        $resolver = $this->createOptionsResolver();
        $resolver = $this->defineCompanyId($resolver);

        return $this->get('me', $resolver->resolve($params), $headers);
    }
}
