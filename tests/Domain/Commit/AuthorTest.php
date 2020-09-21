<?php

declare(strict_types=1);

namespace App\Tests\Domain\Commit;

use App\Domain\Commit\Author;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class AuthorTest extends TestCase
{
    /**
     * @dataProvider getCreationData
     */
    public function testThatCanBeCreatedFromString(
        string $stringParameter,
        string $expectedName,
        string $expectedEmail
    ) {
        $author = Author::fromString($stringParameter);

        self::assertEquals($expectedEmail, $author->email());
        self::assertEquals($expectedName, $author->name());
    }

    /**
     * @return \string[][]
     */
    public function getCreationData(): array
    {
        return [
            ['Leszek Prabucki <leszek.prabucki@gmail.com>', 'Leszek Prabucki', 'leszek.prabucki@gmail.com'],
            ['<leszek.prabucki@gmail.com>', '', 'leszek.prabucki@gmail.com'],
            ['l3l0 <l3l0@cocoders.com>', 'l3l0', 'l3l0@cocoders.com'],
            ['l3l0@cocoders.com', '', 'l3l0@cocoders.com'],
        ];
    }
}
