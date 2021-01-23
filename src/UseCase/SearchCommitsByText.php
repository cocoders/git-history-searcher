<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Domain\Git;
use App\UseCase\SearchCommitsByText\FoundCommits;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SearchCommitsByText implements MessageHandlerInterface
{
    public function __construct(private Git $git, private FoundCommits $foundCommits)
    {}

    public function __invoke(SearchCommitsByText\Command $command): void
    {
        $this->git->cloneRepository(
            $command->repoUri(),
            $command->repoName()
        );

        $commits = $this->git->findCommitsByCommentFragment(
            $command->repoName(),
            $command->searchPhrase()
        );
        $this->foundCommits->setCommits($command->searchPhrase(), $commits);
    }
}
