<?php

declare(strict_types=1);

namespace MoabTech\Procore;

use MoabTech\Procore\Api\ApiInterface;
use MoabTech\Procore\Exception\RuntimeException;
use MoabTech\Procore\HttpClient\Message\ResponseMediator;
use ValueError;

final class Paginator implements PaginatorInterface
{
    /**
     * The default number of entries to request per page.
     *
     * @var int
     */
    private const PER_PAGE = 50;

    /**
     * The default page we start on
     *
     * @var int
     */
    private const PAGE = 1;

    /**
     * The client to use for pagination.
     *
     * @var Client
     */
    private $client;

    /**
     * The number of entries to request per page.
     *
     * @var int
     */
    private $perPage;

    /**
     * The page we are wanting
     *
     * @var int
     */
    private $page;

    /**
     * The pagination result from the API.
     *
     * @var array<string,string>
     */
    private $pagination;

    /**
     * Create a new result pager instance.
     *
     * @param Client   $client
     * @param int|null $perPage
     *
     * @return void
     */
    public function __construct(Client $client, int $perPage = null, int $page = null)
    {
        if (null !== $perPage && ($perPage < 1 || $perPage > 10000)) {
            throw new ValueError(\sprintf('%s::__construct(): Argument #2 ($perPage) must be between 1 and 10000, or null', self::class));
        }

        $this->client = $client;
        $this->perPage = $perPage ?? self::PER_PAGE;
        $this->page = $page ?? self::PAGE;
        $this->pagination = [];
    }

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
    public function fetch(ApiInterface $api, string $method, array $parameters = [])
    {
        $result = $api->perPage($this->perPage)->page($this->page)->$method(...$parameters);

        if (! \is_array($result)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $result;
    }

    /**
     * Check to determine the availability of a next page.
     *
     * @return bool
     */
    public function hasNext()
    {
        return isset($this->pagination['next']);
    }

    /**
     * Fetch the next page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchNext()
    {
        return $this->get('next');
    }

    /**
     * Check to determine the availability of a previous page.
     *
     * @return bool
     */
    public function hasPrevious()
    {
        return isset($this->pagination['prev']);
    }

    /**
     * Fetch the previous page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchPrevious()
    {
        return $this->get('prev');
    }

    /**
     * Fetch the first page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchFirst()
    {
        return $this->get('first');
    }

    /**
     * Fetch the last page.
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    public function fetchLast()
    {
        return $this->get('last');
    }

    /**
     * Refresh the pagination property.
     *
     * @return void
     */
    private function postFetch(): void
    {
        $response = $this->client->getLastResponse();

        $this->pagination = null === $response ? [] : ResponseMediator::getPagination($response);
    }

    /**
     * @param string $key
     *
     * @throws \Http\Client\Exception
     *
     * @return array
     */
    private function get(string $key)
    {
        $pagination = $this->pagination[$key] ?? null;

        if (null === $pagination) {
            return [];
        }

        $result = $this->client->getHttpClient()->get($pagination);

        $content = ResponseMediator::getContent($result);

        if (! \is_array($content)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $content;
    }
}
