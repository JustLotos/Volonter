<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Request;

use App\Validator\UniqueEntityConstraint\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Command
{
    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueEntity(
     *     message="Пользователь с таким email-адресом уже существует",
     *     class="App\Domain\User\Entity\User",
     *     attribute="email",
     * )
     * @Serializer\Type(name="string")
     */
    public $email;

    /**
     * @Assert\Length(min="8", max="255" )
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
