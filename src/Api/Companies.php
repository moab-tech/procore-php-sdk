<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

class Companies extends AbstractApi
{
    public function list()
    {
        $resolver = $this->createOptionsResolver();

        return $this->get('companies', $resolver->resolve());
    }
}
