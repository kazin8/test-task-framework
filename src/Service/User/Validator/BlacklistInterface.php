<?php
declare(strict_types=1);

namespace App\Service\User\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

interface BlacklistInterface
{
    public static function validate(User $entity, ExecutionContextInterface $context, $payload);
}