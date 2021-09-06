<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\PaginatedCollectionDto;
use App\Dto\User\UserDto;

interface UserServiceInterface
{
    public function create(UserDto $dto): UserDto;

    public function update(int $id, UserDto $dto): UserDto;

    public function get(int $id): UserDto;

    public function restore(int $id): UserDto;

    public function delete(int $id): UserDto;

    public function getList(array $filter, string $order, int $limit, int $offset): PaginatedCollectionDto;
}