<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api\Core\Company;

use MoabTech\Procore\Api\AbstractApi;
use MoabTech\Procore\Api\ApiInterface;

class Companies extends AbstractApi implements ApiInterface
{
    public function list(array $params = [], array $headers = [])
    {
        $resolver = $this->createOptionsResolver();

        return $this->get('companies', $resolver->resolve($params), $headers);
    }
}
