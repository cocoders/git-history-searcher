<?php

declare(strict_types=1);

namespace App\Domain;

interface Git
{
    /**
     * Contract: Clone should not throws errors when repo with given name already exists.
     */
    public function cloneRepository(string $uri, string $repoName): void;

    /**
     * Contract: We assume that repository is cloned already and if not should @throws RepositoryNotFound exception
     *
     * @throws RepositoryNotFound
     * @return Commit[]
     */
    public function findCommitsByCommentFragment(string $repoName, string $searchPhrase): array;
}
