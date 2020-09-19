<?php

declare(strict_types=1);

namespace spec\App\Domain;

use App\Domain\Commit;
use App\Domain\Commit\Author;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Commit
 */
class CommitSpec extends ObjectBehavior
{
    function it_allows_to_fetch_values()
    {
        $this->beConstructedThrough(
            'fromBasicInformation',
            [
                'ca82a6dff817ec66f44342007202690a93763949',
                Author::fromString('Leszek Prabucki <leszek.prabucki@gmail.com>'),
                new \DateTimeImmutable('Sat Mar 15 16:40:33 2008 -0700'),
                'Remove unnecessary test'
            ]
        );

        $this->hash()->shouldBe('ca82a6dff817ec66f44342007202690a93763949');
        $this->author()->shouldBeLike(Author::fromString('Leszek Prabucki <leszek.prabucki@gmail.com>'));
        $this->committedAt()->shouldBeLike(new \DateTimeImmutable('Sat Mar 15 16:40:33 2008 -0700'));
        $this->comment()->shouldBe('Remove unnecessary test');
    }
}
