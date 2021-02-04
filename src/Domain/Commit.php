<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Commit\Author;
use DateTimeImmutable;
use InvalidArgumentException;
use JsonSerializable;

class Commit implements JsonSerializable
{
    private function __construct(
        private string $hash,
        private Author $author,
        private DateTimeImmutable $committedAt,
        private string $comment
    ) {}

    public static function fromString(string $comment): self
    {
        $commitMetadata = array_filter(array_map('trim', explode("\n", $comment)));

        $hash = '';
        $author = '';
        $date  = '';
        $commentParts = [];

        foreach ($commitMetadata as $line) {
            if (self::lineContains($line, 'commit')) {
                $hash = self::getContentFromLineWithout($line, 'commit');
                continue;
            }
            if (self::lineContains($line, 'author:')) {
                $author = self::getContentFromLineWithout($line, 'author:');
                continue;
            }
            if (self::lineContains($line, 'date:')) {
                $date = self::getContentFromLineWithout($line, 'date:');
                continue;
            }

            $commentParts[] = trim($line);
        }

        if (!$hash || !$author || !$date) {
            throw new InvalidArgumentException('Hash date or author not found in parsed commit string');
        }
        return self::fromBasicInformation(
            $hash,
            Author::fromString($author),
            new DateTimeImmutable($date),
            implode("\n", $commentParts)
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

    private static function lineContains(string $line, string $phrase): bool
    {
        return mb_stripos($line, sprintf('%s ', $phrase)) === 0;
    }

    private static function getContentFromLineWithout(string $line, string $phrase): string
    {
        return str_ireplace(sprintf('%s ', $phrase), '', $line);
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
