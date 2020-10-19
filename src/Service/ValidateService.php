<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($entity, array $groups = [])
    {
        if (count($groups) > 0) {
            $violations = $this->validator->validate($entity, null, $groups);
        } else {
            $violations = $this->validator->validate($entity);
        }

        if ($violations->count() > 0) {
            $errors = [];
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ValidationException(
                json_encode(['errors' => $errors]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $entity;
    }
}
