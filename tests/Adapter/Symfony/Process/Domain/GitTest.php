<?php

namespace App\Tests\Adapter\Symfony\Process\Domain;

use App\Adapter\Symfony\Process\Domain\Git;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group functional
 */
class GitTest extends TestCase
{
    private ?string $gitRepositoriesDir = null;
    private ?string $testGitRepositoryName = null;

    public function setUp()
    {
        $this->gitRepositoriesDir = __DIR__ . DIRECTORY_SEPARATOR;
        $this->testGitRepositoryName = 'test';
    }

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->gitRepositoriesDir.DIRECTORY_SEPARATOR.$this->testGitRepositoryName);
    }

    public function testThatCloneAndSearchCommitsByCommentFragment()
    {
        $git = new Git($this->gitRepositoriesDir);
        $git->cloneRepository('https://github.com/cocoders/beer-menu-api.git', $this->testGitRepositoryName);
        $commits = iterator_to_array($git->findCommitsByCommentFragment($this->testGitRepositoryName, 'php'));

        self::assertCount(2, $commits);
        self::assertEquals('1a493326d1e160c1b8ac01bd69342ab7ac5a6c20', $commits[0]->hash());
        self::assertEquals('d1aa240588be6c57fb6aba04f6af2cd53657eb6d', $commits[1]->hash());
    }

    public function testThatCloneAndSearchCommitsByCommentFragmentWhenIsNotFoundInRepository()
    {
        $git = new Git($this->gitRepositoriesDir);
        $git->cloneRepository('https://github.com/cocoders/beer-menu-api.git', $this->testGitRepositoryName);
        $commits = iterator_to_array($git->findCommitsByCommentFragment(
            $this->testGitRepositoryName,
            'veryLongText'.random_bytes(23)
        ));

        self::assertCount(0, $commits);
    }
}
