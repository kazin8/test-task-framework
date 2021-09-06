<?php

declare(strict_types = 1);

namespace App\Dto;

use Doctrine\Common\Collections\Collection;

/**
 * Class PaginatedCollection.
 */
class PaginatedCollectionDto
{
    /**
     * @var Collection
     */
    private Collection $data;
    /**
     * @var int
     */
    private int $offset;
    /**
     * @var int
     */
    private int $limit;
    /**
     * @var int
     */
    private int $total;

    /**
     *
     * @param Collection $data
     * @param int        $offset
     * @param int        $limit
     * @param int|null   $total
     */
    public function __construct(Collection $data, int $offset = 0, int $limit = 10, ?int $total = null)
    {
        $this->data     = $data;
        $this->offset   = $offset;
        $this->limit    = $limit;
        $this->total    = $total ?? \count($data);
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    /**
     * @param Collection $data
     *
     * @return self
     */
    public function setData(Collection $data): PaginatedCollectionDto
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return self
     */
    public function setOffset(int $offset): PaginatedCollectionDto
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return self
     */
    public function setLimit(int $limit): PaginatedCollectionDto
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     *
     * @return self
     */
    public function setTotal(int $total): PaginatedCollectionDto
    {
        $this->total = $total;

        return $this;
    }
}
