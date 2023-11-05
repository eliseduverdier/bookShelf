<?php

namespace App\Entity;

use App\Services\Helpers\Utils;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'books')]
class Book
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: 'string')]
    public string $title;
    #[ORM\Column(type: 'string')]
    public string $author;
    #[ORM\Column(type: 'string')]
    public string $slug;
    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $summary = '';
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $private_book;
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $private_summary;
    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTime $finished_at = null;
    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTime $abandonned_at;
    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTime $deletedAt;
    #[ORM\Column(type: 'datetime', nullable: true)]
    public \DateTime $createdAt;
    #[ORM\Column(type: 'datetime', nullable: true)]
    public \DateTime $updatedAt;
    #[ORM\ManyToOne(targetEntity: Type::class)]
    public Type $type;
    #[ORM\ManyToOne(targetEntity: Note::class)]
    public Note $note;
    #[ORM\ManyToOne(targetEntity: User::class)]
    public UserInterface $user;

    public function __construct(
        UserInterface $user,
        string $title,
        string $author,
        Type $type,
        Note $note,
        ?\DateTime $finished_at = null,
    ){
        $this->slug = Utils::slugify("{$user->getUserIdentifier()}_{$title}_$author");
        $this->user = $user;
        $this->title = $title;
        $this->author = $author;
        $this->type = $type;
        $this->note = $note;
        $this->finished_at = $finished_at;
        $this->private_book = false;
        $this->private_summary = false;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

}
