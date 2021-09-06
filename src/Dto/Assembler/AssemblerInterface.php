<?php
declare(strict_types=1);

namespace App\Dto\Assembler;

use App\Dto\DtoInterface;
use App\Entity\EntityInterface;

interface AssemblerInterface
{
    public function getDtoFromEntity(EntityInterface $entity): DtoInterface;

    public function mapEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface;
}
