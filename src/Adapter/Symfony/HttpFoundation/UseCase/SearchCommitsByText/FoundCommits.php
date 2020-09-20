<?php

declare(strict_types=1);

namespace App\Adapter\Symfony\HttpFoundation\UseCase\SearchCommitsByText;

use App\Domain\Commit;
use App\UseCase\SearchCommitsByText\FoundCommits as FoundCommitsInterface;
use Traversable;

class FoundCommits implements FoundCommitsInterface
{
    /**
     * @param Traversable<Commit> $commits
     */
    public function setCommits(string $searchParams, Traversable $commits): void
    {
    }
}
