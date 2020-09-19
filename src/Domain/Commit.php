<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Commit\Author;
use DateTimeImmutable;

class Commit
{
    private string $hash;
    private Author $author;
    private DateTimeImmutable $committedAt;
    private string $comment;

    private function __construct(
        string $hash,
        Author $author,
        DateTimeImmutable $committedAt,
        string $comment
    ) {
        $this->hash = $hash;
        $this->author = $author;
        $this->committedAt = $committedAt;
        $this->comment = $comment;
    }

    public static function fromBasicInformation(
        string $hash,
        Author $author,
        DateTimeImmutable $committedAt,
        string $comment
    ): self {
        return new Commit(
            $hash,
            $author,
            $committedAt,
            $comment
        );
    }

    public function hash(): string
    {
        return $this->hash;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function committedAt(): DateTimeImmutable
    {
        return $this->committedAt;
    }

    public function comment(): string
    {
        return $this->comment;
    }
}
