<?php

declare(strict_types=1);

namespace App\Domain;

use DomainException;

final class RepositoryNotFound extends DomainException
{
    public static function forName(string $repoName): self
    {
        return new RepositoryNotFound(sprintf('Repository with name "%s" not found', $repoName));
    }
}
