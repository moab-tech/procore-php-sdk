<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

class Me extends AbstractApi
{
    public function show(array $params = [], array $headers = [])
    {
        return $this->get('me', $params, $headers);
    }
}
