<?php

declare(strict_types=1);

namespace App\Adapter\InMemory\UseCase\SearchCommitsByText;

use App\Domain\Commit;
use App\UseCase\SearchCommitsByText\FoundCommits as FoundCommitsInterface;
use Traversable;

class FoundCommits implements FoundCommitsInterface
{
    /**
     * @var array<string, Commit[]>
     */
    private array $foundCommits = [];

    public function setCommits(string $searchParams, Traversable $commits): void
    {
        $this->foundCommits[$searchParams] = iterator_to_array($commits);
    }

    /**
     * @return Commit[]
     */
    public function foundCommitsByPhrase(string $searchParams): array
    {
        return $this->foundCommits[$searchParams] ?? [];
    }
}
