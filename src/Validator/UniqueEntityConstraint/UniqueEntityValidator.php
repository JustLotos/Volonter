<?php

declare(strict_types=1);

namespace App\Validator\UniqueEntityConstraint;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class UniqueEntityValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint) : void
    {
        /** @var UniqueEntity $constraint */

        if (! $constraint->class) {
            throw new ConstraintDefinitionException(
                'Не установлено значение параметра класс class в аннотации UniqueEntity'
            );
        }

        if (! $constraint->attribute) {
            throw new ConstraintDefinitionException(
                'Не установлено значение свойства attribute в аннотации UniqueEntity'
            );
        }

        $existingEntity = $this->entityManager->getRepository($constraint->class)->findOneBy([$constraint->attribute => $value]);

        if (! $existingEntity) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
