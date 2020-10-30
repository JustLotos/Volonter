<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\Types\ConfirmToken;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use DomainException;
use DateTimeImmutable;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="users_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 *     @ORM\UniqueConstraint(columns={"confirm_token_token"})
 * })
 */
class User implements UserInterface
{
    public const STATUS_WAIT = 'WAIT';
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_BLOCKED = 'BLOCKED';

    /**
     * @var Id
     * @ORM\Column(type="users_user_id")
     * @ORM\Id
     * @Serializer\Type(name="string")
     * @Serializer\Groups({User::GROUP_DETAIL})
     */
    private $id;

    /**
     * @var Email
     * @ORM\Column(type="users_user_email", name="email")
     * @Serializer\Type(name="string")
     * @Serializer\Groups({User::GROUP_SIMPLE})
     */
    private $email;
    /**
     * @var Email|null
     * @ORM\Column(type="users_user_email", nullable=true)
     */
    private $temporaryEmail;

    /**
     * @var Password
     * @ORM\Column(type="users_user_password", name="password")
     */
    private $password;

    /**
     * @var ConfirmToken|null
     * @ORM\Embedded(class="App\Domain\User\Entity\Types\ConfirmToken", columnPrefix="confirm_token_")
     */
    private $confirmToken;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     * @Serializer\Groups({User::GROUP_SIMPLE})
     */
    private $status;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({User::GROUP_DETAIL})
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({User::GROUP_DETAIL})
     */
    private $updatedAt;

    /**
     * @var Role
     * @ORM\Column(type="users_user_role", length=16)
     * @Serializer\Type(name="string")
     * @Serializer\Groups({User::GROUP_SIMPLE})
     */
    private $role;

    public const GROUP_SIMPLE = 'GROUP_SIMPLE';
    public const GROUP_DETAIL = 'GROUP_DETAIL';

    private function __construct(Id $id, DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }

    public static function createByEmail(Id $id, DateTimeImmutable $date, Role $role, Email $email, Password $password): self
    {
        Assert::email($email);
        $user = new self($id, $date);
        $user->role = $role;
        $user->email = $email;
        $user->password = $password;
        $user->status = self::STATUS_WAIT;
        return $user;
    }

    public function requestRegisterByEmail(ConfirmToken $token): ConfirmToken
    {
        if (!$this->isWait()) {
            throw new DomainException('User is already confirmed.');
        }
        if ($this->confirmToken && !$this->confirmToken->isExpiredToNow()) {
            throw new DomainException('Resetting is already requested.');
        }

        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
        return $this->confirmToken;
    }

    public function confirmRegisterByEmail(DateTimeImmutable $date = null): void
    {
        if (!$this->confirmToken && $this->isActive()) {
            throw new DomainException('Confirm user in not requested.');
        }
        if ($this->confirmToken->isExpiredTo($date ? $date : new DateTimeImmutable())) {
            throw new DomainException('Confirm token is expired.');
        }

        $this->confirmToken = null;
        $this->activate();
    }

    public function requestResetPassword(ConfirmToken $token): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->confirmToken && !$this->confirmToken->isExpiredToNow()) {
            throw new DomainException('Resetting is already requested.');
        }

        $this->block();
        $this->confirmToken = $token;
    }

    public function confirmResetPassword(Password $password, DateTimeImmutable $date = null): void
    {
        if (!$this->confirmToken && $this->isActive()) {
            throw new DomainException('Resetting is not requested.');
        }

        if ($this->confirmToken && $this->confirmToken->isExpiredTo($date ? $date : new DateTimeImmutable())) {
            throw new DomainException('Reset token is expired.');
        }

        $this->activate();
        $this->password = $password;
        $this->confirmToken = null;
    }

    public function requestChangeEmail(ConfirmToken $token, Email $email): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->confirmToken && !$this->confirmToken->isExpiredToNow() && $this->temporaryEmail) {
            throw new DomainException('Resetting is already requested.');
        }
        if ($this->getEmail()->isEqual($email)) {
            throw new DomainException('Email is same.');
        }

        $this->block();
        $this->confirmToken = $token;
        $this->temporaryEmail = $email;
    }

    public function confirmChangeEmail(DateTimeImmutable $date = null): void
    {
        if (!$this->confirmToken && $this->isActive() && !$this->temporaryEmail) {
            throw new DomainException('Changing is not requested.');
        }
        if ($this->confirmToken->isExpiredTo($date ? $date : new DateTimeImmutable())) {
            throw new DomainException('Reset token is expired.');
        }
        $this->email = $this->temporaryEmail;
        $this->temporaryEmail = null;
        $this->confirmToken = null;
        $this->activate();
    }

    public function resetTemporaryEmail(): self
    {
        $this->temporaryEmail = null;
        return $this;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new DomainException('Role is already same.');
        }
        $this->role = $role;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('User is already active.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->updatedAt = new DateTimeImmutable();
    }
    public function block(): void
    {
        if ($this->isBlocked()) {
            throw new DomainException('User is already blocked.');
        }
        $this->status = self::STATUS_BLOCKED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function getId(): Id
    {
        return $this->id;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }
    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }
    public function getRole(): Role
    {
        return $this->role;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getPassword(): Password
    {
        return $this->password;
    }
    public function getRoles(): array
    {
        return [$this->role->getName()];
    }
    public function getUsername(): string
    {
        return $this->getEmail()->getValue();
    }
    public function getDateCreated(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getDateUpdated(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
    public function getTemporaryEmail(): ?Email
    {
        return $this->temporaryEmail;
    }

    public function setPassword(Password $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
