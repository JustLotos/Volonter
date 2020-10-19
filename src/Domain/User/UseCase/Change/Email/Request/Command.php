<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Email\Request;

use App\Validator\ExistEntityConstraint\ExistEntity;
use App\Validator\UniqueEntityConstraint\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Command
{
    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ExistEntity(
     *     class="App\Domain\User\Entity\User",
     *     attribute="email",
     *     message="Пользователя с таким адресом не существует"
     * )
     * @Serializer\Type(name="string")
     */
    public $oldEmail;

    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueEntity(
     *     class="App\Domain\User\Entity\User",
     *     attribute="email",
     *     message="Пользователя с таким адресом уже существует"
     * )
     * @Serializer\Type(name="string")
     */
    public $newEmail;
}