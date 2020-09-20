<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Commit;
use App\UseCase\SearchCommitsByText;
use App\Adapter\InMemory\UseCase\SearchCommitsByText\FoundCommits;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class SearchGitRepositoryCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:search-git-repository';
    private MessageBusInterface $bus;
    private FoundCommits $foundCommits;

    public function __construct(MessageBusInterface $bus, FoundCommits $foundCommits)
    {
        parent::__construct(null);
        $this->bus = $bus;
        $this->foundCommits = $foundCommits;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Output commits from git repository which has found phrase in comment')
            ->addArgument('repositoryName', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('phrase', InputArgument::REQUIRED, 'Search phrase')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /**
         * @var string $name
         */
        $name = $input->getArgument('repositoryName');
        /**
         * @var string $phrase
         */
        $phrase = $input->getArgument('phrase');

        $this->bus->dispatch(new SearchCommitsByText\Command(
            $name,
            $phrase
        ));

        $commits = $this->foundCommits->foundCommitsByPhrase($phrase);
        $io->table(
            ['hash', 'author', 'comments', 'committedAt'],
            array_map(
                static function (Commit $commit): array {
                    return [
                       'hash' => $commit->hash(),
                       'author' => $commit->author(),
                       'comments' => $commit->comment(),
                       'committedAt' => $commit->committedAt()->format(DATE_ATOM)
                    ];
                },
                $commits
            )
        );

        return Command::SUCCESS;
    }
}
