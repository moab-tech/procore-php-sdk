<?php

declare(strict_types=1);

namespace MoabTech\Procore;

use MoabTech\Procore\Api\ApiInterface;

interface PaginatorInterface
{
    /**
     * Fetch a single result from an api call.
     *
     * @param ApiInterface $api
     * @param string       $method
     * @param array        $parameters
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetch(ApiInterface $api, string $method, array $parameters = []);

    /**
     * Check to determine the availability of a next page.
     *
     * @return bool
     */
    public function hasNext();

    /**
     * Fetch the next page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchNext();

    /**
     * Check to determine the availability of a previous page.
     *
     * @return bool
     */
    public function hasPrevious();

    /**
     * Fetch the previous page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchPrevious();

    /**
     * Fetch the first page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchFirst();

    /**
     * Fetch the last page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchLast();
}
