<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Reset\ByEmail\Confirm;

use App\Validator\ExistEntityConstraint\ExistEntity;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     * @ExistEntity(
     *     class="App\Domain\User\Entity\User",
     *     attribute="confirmToken.token",
     *     message="Пользователя с таким токеном не существует"
     * )
     */
    public $token;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[a-z])\S*$/",
     *     message="Password must contain at least 1 lowercase letter"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[A-Z])\S*$/",
     *     message="Password must contain at least 1 uppercase letter"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[\d])\S*$/",
     *     message="Password must contain at least 1 number"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[\d])\S*$/",
     *     message="Password must contain at least 1 number"
     * )
     * @Assert\Length(min="8", max="255" )
     * @Serializer\Type(name="string")
     */
    public $password;

    /**
     * @Assert\NotBlank()
     * @Assert\IdenticalTo(propertyPath="password")
     * @Serializer\Type(name="string")
     */
    public $plainPassword;
}

