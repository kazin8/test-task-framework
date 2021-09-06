<?php
namespace App\Dto;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractDto implements DtoInterface
{
    protected ConstraintViolationListInterface $errors;

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors ?? new ConstraintViolationList();
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return AbstractDto
     */
    public function setErrors(ConstraintViolationListInterface $errors): AbstractDto
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return int
     */
    public function getErrorsCount(): int
    {
        return count($this->getErrors());
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return (bool) $this->getErrorsCount();
    }
}
