<?php
declare(strict_types=1);

namespace App\Service\Journal\Adapter;

use App\Dto\Journal\JournalDto;

interface AdapterInterface
{
    /**
     * @param JournalDto[] $dtoCollection
     */
    public function store(array $dtoCollection): bool;
}