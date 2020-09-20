<?php

declare(strict_types=1);

namespace App\UseCase\SearchCommitsByText;

use App\Domain\Commit;
use Traversable;

interface FoundCommits
{
    /**
     * @param Traversable<Commit> $commits
     */
    public function setCommits(string $searchParams, Traversable $commits): void;
}
