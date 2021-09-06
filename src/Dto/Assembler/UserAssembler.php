<?php
declare(strict_types=1);

namespace App\Dto\Assembler;

use App\Dto\DtoInterface;
use App\Dto\User\UserDto;
use App\Entity\EntityInterface;
use App\Entity\User;

class UserAssembler implements AssemblerInterface
{
    /**
     * @param User $entity
     * @return UserDto
     */
    public function getDtoFromEntity(EntityInterface $entity): DtoInterface
    {
        $dto = new UserDto();
        $dto
            ->setId($entity->getId())
            ->setName($entity->getName())
            ->setEmail($entity->getEmail())
            ->setCreated($entity->getCreated())
            ->setDeleted($entity->getDeleted())
            ->setNotes($entity->getNotes());

        return $dto;
    }

    /**
     * @param User $entity
     * @param UserDto $dto
     * @return User
     */
    public function mapEntityFromDto(EntityInterface $entity, DtoInterface $dto): User
    {
        $entity
            ->setName($dto->getName() ?? $entity->getName())
            ->setEmail($dto->getEmail() ?? $entity->getEmail())
            ->setCreated($dto->getCreated() ?? $entity->getCreated())
            ->setDeleted($dto->getDeleted() ?? $entity->getDeleted())
            ->setNotes($dto->getNotes() ?? $entity->getNotes());

        return $entity;
    }
}
