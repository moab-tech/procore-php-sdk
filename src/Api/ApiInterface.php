<?php

declare(strict_types=1);

namespace MoabTech\Procore\Api;

interface ApiInterface
{
    /**
     * Create a new instance with the given per page parameter.
     *
     * This must be an integer between 1 and 100.
     *
     * @param int|null $perPage
     *
     * @return static
     */
    public function perPage(?int $perPage);

    /**
     * Create a new instance with the given page parameter.
     *
     * This must be an integer between 1 and 100.
     *
     * @param int|null $page
     *
     * @return static
     */
    public function page(?int $page);
}
