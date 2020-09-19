<?php

declare(strict_types=1);

namespace spec\App\Domain\Commit;

use App\Domain\Commit\Author;
use App\Domain\Commit\InvalidAuthorConstructionArgument;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Author
 */
class AuthorSpec extends ObjectBehavior
{
    function it_can_be_created_from_string()
    {
        $this->beConstructedThrough('fromString', ['Leszek Prabucki <leszek.prabucki@gmail.com>']);

        $this->name()->shouldBe('Leszek Prabucki');
        $this->email()->shouldBe('leszek.prabucki@gmail.com');
    }

    function it_cannot_be_created_from_string_which_is_not_match_that_one_defined_in_RFC()
    {
        $this->beConstructedThrough('fromString', ['invalid string']);

        $this
            ->shouldThrow(InvalidAuthorConstructionArgument::forInvalidString('invalid string'))
            ->duringInstantiation();
    }
}
