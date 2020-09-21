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

    function it_can_be_created_from_string()
    {
        $this->beConstructedThrough(
            'fromString',
            [
                <<<EOF
                    commit ca82a6dff817ec66f44342007202690a93763949\n
                    Author: Leszek Prabucki <leszek.prabucki@gmail.com>\n
                    Date:   Sat May 18 10:22:15 2019 +0200\n
                    \n
                       Simple php container added symfony code\n
                       other comment\n
                    \n
                EOF
            ]
        );

        $this->hash()->shouldBe('ca82a6dff817ec66f44342007202690a93763949');
        $this->author()->shouldBeLike(Author::fromString('Leszek Prabucki <leszek.prabucki@gmail.com>'));
        $this->committedAt()->format(DATE_ATOM)->shouldBe('2019-05-18T10:22:15+02:00');
        $this->comment()->shouldBe("Simple php container added symfony code\nother comment");
    }

    function it_cannot_be_created_from_string_when_given_string_has_invalid_form()
    {
        $this->beConstructedThrough(
            'fromString',
            [
                <<<EOF
                    Author: Leszek Prabucki <leszek.prabucki@gmail.com>\n
                    Date:   Sat May 18 10:22:15 2019 +0200\n
                    \n
                       Simple php container added symfony code\n
                       other comment\n
                    \n
                EOF
            ]
        );

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
