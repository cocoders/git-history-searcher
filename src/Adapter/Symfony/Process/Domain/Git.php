<?php

declare(strict_types=1);

namespace App\Adapter\Symfony\Process\Domain;

use App\Domain\Commit;
use App\Domain\Git as GitInterface;
use Iterator;
use Symfony\Component\Process\Process;
use Traversable;

final class Git implements GitInterface
{
    private string $gitRepositoriesDirectory;

    public function __construct(string $gitRepositoriesDirectory)
    {
        $this->gitRepositoriesDirectory = $gitRepositoriesDirectory;
    }

    public function cloneRepository(string $uri, string $repoName): void
    {
        $cloneProcess = new Process(
            ['git', 'clone', $uri, sprintf('%s/%s', $this->gitRepositoriesDirectory, $repoName)]
        );
        $cloneProcess->run();
    }

    public function findCommitsByCommentFragment(string $repoName, string $searchPhrase): Traversable
    {
        $gitDir = sprintf('%s/%s/.git', $this->gitRepositoriesDirectory, $repoName);
        $this->fetchAll($gitDir);

        $logProcess = new Process(
            ['git', '--git-dir', $gitDir, 'log', '-i', '--no-merges', '--grep', $searchPhrase, '--all']
        );
        $logProcess->start();
        /**
         * @var Iterator<string, string> $commitIterator
         */
        $commitIterator = $logProcess->getIterator(Process::ITER_SKIP_ERR | Process::ITER_NON_BLOCKING);

        foreach ($commitIterator as $type => $data) {
            if ($type === Process::OUT && trim($data)) {
                yield Commit::fromString(trim($data));
            }
        }
    }

    private function fetchAll(string $gitDir): void
    {
        $fetchProcess = new Process(
            ['git', '--git-dir', $gitDir, 'fetch', '--all']
        );
        $fetchProcess->run();
    }
}
