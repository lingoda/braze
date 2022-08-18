<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation as SymfonyValidation;

/**
 * @TODO: Replace with Symfony Validation once migrated to 5.3
 */
final class Validation
{
    /**
     * @return callable(mixed, ConstraintViolationListInterface): bool
     */
    public static function createIsValidCallback(Constraint ...$constraints): callable
    {
        $validator = SymfonyValidation::createValidator();

        return static function ($value, &$violations = null) use ($constraints, $validator): bool {
            $violations = $validator->validate($value, $constraints);

            return 0 === $violations->count();
        };
    }
}
