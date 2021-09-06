<?php
declare(strict_types=1);

namespace App\Service\User\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class NameBlacklistValidator implements BlacklistInterface
{
    public static function validate(User $entity, ExecutionContextInterface $context, $payload)
    {
        print_r($payload);
        return true;

        $context->buildViolation('This name sounds badly')
            ->atPath('name')
            ->addViolation();
    }
}