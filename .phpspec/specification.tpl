<?php

declare(strict_types=1);

namespace %namespace%;

use %subject%;
use PhpSpec\ObjectBehavior;

/**
 * @mixin %subject_class%
 */
class %name% extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(%subject_class%::class);
    }
}
