<?php

declare(strict_types=1);

namespace App\Validator\ExistEntityConstraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ExistEntity extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Объект с со свойством "{{ value }}" не существует';

    public $attribute = null;

    public $class = null;

    public function validatedBy()
    {
        return 'ExistEntity';
    }
}
