<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

class Companies extends AbstractApi implements ApiInterface
{
    public function list(array $params = [], array $headers = [])
    {
        $resolver = $this->createOptionsResolver();

        return $this->get('companies', $resolver->resolve($params), $headers);
    }
}
