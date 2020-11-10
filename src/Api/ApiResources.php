<?php

namespace MoabTech\Procore\Api;

trait ApiResources
{
    /**
     * @return DeployKeys
     */
    public function companies()
    {
        return new Companies($this);
    }
}
