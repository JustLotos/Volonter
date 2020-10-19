<?php

declare(strict_types=1);

namespace App\Validator\UniqueEntityConstraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEntity extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Объект с со свойством "{{ value }}" уже существует';

    public $attribute = null;

    public $class = null;

    public function validatedBy()
    {
        return 'UniqueEntity';
    }
}
