<?php

namespace App\Tests\Domain;

use App\Domain\Commit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class CommitTest extends TestCase
{
    /**
     * @dataProvider getValidStringForFromString
     */
    public function testThatBeCreatedFromString(string $string)
    {
        $commit = Commit::fromString($string);

        self::assertInstanceOf(Commit::class, $commit);
    }

    /**
     * @dataProvider getInvalidStringForFromString
     */
    public function testThatCannotBeCreatedFromInvalidString(string $invalidString)
    {
        $this->expectException(InvalidArgumentException::class);

        Commit::fromString($invalidString);
    }

    public function getValidStringForFromString()
    {
        return [
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
            ],
            [
                <<<EOF
                    commit ca82a6dff817ec66f44342007202690a93763949\n
                    Author: Leszek Prabucki <leszek.prabucki@gmail.com>\n
                    Date:   Sat May 18 10:22:15 2019 +0200\n
                    \n
                EOF
            ],
            [
                <<<EOF
                    commit ca82a6dff817ec66f44342007202690a93763949\n
                    Author: Leszek Prabucki <leszek.prabucki@gmail.com>\n
                    Date:   Sat May 18 10:22:15 2019 +0200\n
                EOF
            ]
        ];
    }

    public function getInvalidStringForFromString()
    {
        return [
            [''],
            [
               <<<EOF
                    Author: Leszek Prabucki <leszek.prabucki@gmail.com>\n
                    Date:   Sat May 18 10:22:15 2019 +0200\n
                    \n
                       Simple php container added symfony code\n
                       other comment\n
                    \n
               EOF
            ],
            [
                <<<EOF
                    commit ca82a6dff817ec66f44342007202690a93763949\n
                    Date:   Sat May 18 10:22:15 2019 +0200\n
                    \n
                       Simple php container added symfony code\n
                       other comment\n
                    \n
                EOF
            ],
            [
                <<<EOF
                    commit ca82a6dff817ec66f44342007202690a93763949\n
                    Author: Leszek Prabucki <leszek.prabucki@gmail.com>\n
                    \n
                       Simple php container added symfony code\n
                       other comment\n
                    \n
                EOF
            ]
        ];
    }
}
