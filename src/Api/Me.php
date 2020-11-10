<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

class Me extends AbstractApi
{
    public function show(array $params = [])
    {
        $resolver = $this->createOptionsResolver();

        return $this->get('me', $resolver->resolve($params));
    }
}
