<?php

declare(strict_types=1);

namespace spec\App\UseCase;

use App\Domain\Commit;
use App\UseCase\SearchCommitsByText;
use App\UseCase\SearchCommitsByText\FoundCommits;
use App\Domain\Git;
use ArrayIterator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @mixin SearchCommitsByText
 */
class SearchCommitsByTextSpec extends ObjectBehavior
{
    function let(Git $git, FoundCommits $foundCommits)
    {
        $this->beConstructedWith($git, $foundCommits);
    }

    function it_is_messenger_message_handler()
    {
        $this->shouldHaveType(MessageHandlerInterface::class);
    }

    function it_clone_git_repository_finds_commits_by_comments_in_that_repository_fetch_commits(
        Git $git,
        Commit $commit,
        FoundCommits $foundCommits
    ) {
        $command = new SearchCommitsByText\Command(
            'git@github.com:cocoders/beer-menu-api.git',
            'symfony'
        );
        $git->cloneRepository(
            'git@github.com:cocoders/beer-menu-api.git',
            md5('git@github.com:cocoders/beer-menu-api.git')
        )->shouldBeCalled();
        $git
            ->findCommitsByCommentFragment(
                md5('git@github.com:cocoders/beer-menu-api.git'),
                'symfony'
            )
            ->willReturn(new ArrayIterator([$commit]))
        ;
        $foundCommits->setCommits('symfony', new ArrayIterator([$commit]))->shouldBeCalled();
        $this->__invoke($command);
    }
}
