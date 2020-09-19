<?php

declare(strict_types=1);

namespace App\Adapter\Symfony\HttpFoundation\UseCase\SearchCommitsByText;

use App\UseCase\SearchCommitsByText\FoundCommits as FoundCommitsInterface;

class FoundCommits implements FoundCommitsInterface
{
    public function setCommits(string $searchParams, array $commits): void
    {
    }
}
