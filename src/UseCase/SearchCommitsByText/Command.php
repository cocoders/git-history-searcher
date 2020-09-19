<?php

declare(strict_types=1);

namespace App\UseCase\SearchCommitsByText;

/**
 * @psalm-immutable
 */
final class Command
{
    private string $repoUri;
    private string $searchPhrase;

    public function __construct(string $repoUri, string $searchPhrase)
    {
        $this->repoUri = $repoUri;
        $this->searchPhrase = $searchPhrase;
    }

    public function repoUri(): string
    {
        return $this->repoUri;
    }

    public function searchPhrase(): string
    {
        return $this->searchPhrase;
    }

    public function repoName(): string
    {
        return md5($this->repoUri);
    }
}
