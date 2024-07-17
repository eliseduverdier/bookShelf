<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'bookshelf_users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: 'string')]
    public string $username;

    #[ORM\Column(type: 'string')]
    protected string $password;

    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $favColor = null;

    #[ORM\Column(type: 'datetime')]
    public \DateTime $lastConnectedAt;

    private array $roles = [];

    public function __construct($username)
    {
        $this->username = $username;
        $this->lastConnectedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /** returns hashed password */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return array_unique(array_merge($this->roles, ['ROLE_USER']));
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getFavColor(): string
    {
        return $this->favColor;
    }

}
