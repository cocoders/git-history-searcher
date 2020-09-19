<?php

declare(strict_types=1);

namespace App\Domain\Commit;

use Prophecy\Exception\InvalidArgumentException;

final class InvalidAuthorConstructionArgument extends InvalidArgumentException
{
    public static function forInvalidString(string $argument): self
    {
        return new InvalidAuthorConstructionArgument(
            sprintf('Cannot be created for "%s" argument. Its not match RFC', $argument)
        );
    }
}
