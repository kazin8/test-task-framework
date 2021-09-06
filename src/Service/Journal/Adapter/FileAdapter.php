<?php
declare(strict_types=1);

namespace App\Service\Journal\Adapter;

use App\Dto\Journal\JournalDto;

class FileAdapter implements AdapterInterface
{
    /**
     * @param JournalDto[] $dtoCollection
     * @return bool
     * @throws \JsonException
     */
    public function store(array $dtoCollection): bool
    {
        foreach ($dtoCollection as $dto) {
            $json = json_encode($dto, JSON_THROW_ON_ERROR);
            // реализация тут
        }

        return true;
    }
}