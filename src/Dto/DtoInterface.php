<?php
declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface DtoInterface
{
    public function getErrors(): ConstraintViolationListInterface;

    public function setErrors(ConstraintViolationListInterface $errors): DtoInterface;

    public function getErrorsCount(): int;

    public function isError(): bool;
}
