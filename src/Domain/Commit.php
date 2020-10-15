<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Commit\Author;
use DateTimeImmutable;
use InvalidArgumentException;
use JsonSerializable;

class Commit implements JsonSerializable
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

    public static function fromString(string $comment): self
    {
        $commitMetadata = array_filter(array_map('trim', explode("\n", $comment)));

        $hash = '';
        $author = '';
        $date  = '';
        $comment = [];

        foreach ($commitMetadata as $line) {
            if (mb_stripos($line, 'commit ') === 0) {
                $hash = trim(str_ireplace('commit ', '', $line));
                continue;
            }
            if (mb_stripos($line, 'author:',) === 0) {
                $author = trim(str_ireplace('author:', '', $line));
                continue;
            }
            if (mb_stripos($line, 'date:') === 0) {
                $date = trim(str_ireplace('date:', '', $line));
                continue;
            }

            $comment[] = trim($line);
        }

        if (!$hash || !$author || !$date) {
            throw new InvalidArgumentException('Hash date or author not found in parsed commit string');
        }
        return self::fromBasicInformation(
            $hash,
            Author::fromString($author),
            new DateTimeImmutable($date),
            implode("\n", $comment)
        );
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

    /**
     * @return array{hash: string, author: string, comment: string, committedAt: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'hash' => $this->hash(),
            'author' => $this->author(),
            'comment' => $this->comment(),
            'committedAt' => $this->committedAt()->format(DATE_ATOM)
        ];
    }
}
