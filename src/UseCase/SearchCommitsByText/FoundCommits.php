<?php

declare(strict_types=1);

namespace App\UseCase\SearchCommitsByText;

use App\Domain\Commit;

interface FoundCommits
{
    /**
     * @param Commit[] $commits
     */
    public function setCommits(string $searchParams, array $commits): void;
}
