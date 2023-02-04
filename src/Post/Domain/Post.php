<?php

declare(strict_types=1);

namespace App\Post\Domain;

use App\Shared\Domain\AggregateRoot;
use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'post')]
class Post extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(length: 20)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $summary;

    #[ORM\Column]
    private string $description;

    public function __construct(string $id, string $title, string $summary, string $description)
    {
        $this->id = Uuid::fromString($id);
        $this->title = $title;
        $this->summary = $summary;
        $this->description = $description;
    }

    public static function new(string $id, string $title, string $summary, string $description): self
    {
        Assert::that($id)->uuid();
        Assert::that($title)->maxLength(20)->notBlank()->notRegex('/^qwerty/i', 'Title should not start with "Qwerty"');
        Assert::that($summary)->maxLength(255)->notBlank();
        Assert::that($description);

        return new self($id, $title, $summary, $description);
    }

    public static function validateId(string $id): string
    {
        Assert::that($id)->uuid();

        return $id;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
