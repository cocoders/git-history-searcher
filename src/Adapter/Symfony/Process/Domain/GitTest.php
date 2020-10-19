<?php

declare(strict_types=1);

namespace App\Adapter\Symfony\Process\Domain;

use App\Domain\Commit;
use App\Domain\Commit\Author;
use App\Domain\Git as GitInterface;
use Traversable;

final class GitTest implements GitInterface
{
    public function cloneRepository(string $uri, string $repoName): void
    {
    }

    public function findCommitsByCommentFragment(string $repoName, string $searchPhrase): Traversable
    {
        /** @var array{hash: string, author: array{name: string, email:string}, committedAt: string, comment: string} $basicInformation */
        foreach ($this->exampleCommits() as $basicInformation) {
            yield Commit::fromBasicInformation(
                $basicInformation['hash'],
                Author::fromArray($basicInformation['author']),
                new \DateTimeImmutable($basicInformation['committedAt']),
                $basicInformation['comment'],
            );
        }

    }

    /**
     * @return array{
     *  hash: string,
     *  author: array{name: string, email: string},
     *  committedAt: string,
     *  comment: string}[]
     */
    private function exampleCommits(): array{
        return [
            [
                'hash' => '698f8dc751d955a4bdfe1e41cac9bd53ab81b431',
                'author' => ['name' => 'Piotr', 'email' => 'some@email.com'],
                'committedAt' => '2020-09-19T14:43:11+02:00',
                'comment' => 'Share cache between jobs'
            ]
        ];
    }
}
