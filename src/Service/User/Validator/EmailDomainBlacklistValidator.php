<?php
declare(strict_types=1);

namespace App\Service\User\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EmailDomainBlacklistValidator implements BlacklistInterface
{
    public static function validate(User $entity, ExecutionContextInterface $context, $payload)
    {
        print_r($payload);
        return true;

        $context->buildViolation('This email sounds badly')
            ->atPath('email')
            ->addViolation();
    }
}