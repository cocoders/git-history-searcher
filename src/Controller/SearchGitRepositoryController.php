<?php

declare(strict_types=1);

namespace App\Controller;

use App\Adapter\InMemory\UseCase\SearchCommitsByText\FoundCommits;
use App\Form\SymfonyFormErrorsChecker;
use App\Form\Type\SearchGitRepositoryType;
use App\UseCase\SearchCommitsByText;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class SearchGitRepositoryController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $bus,
        private FoundCommits $foundCommits,
        private SymfonyFormErrorsChecker $formErrorsChecker
    ) {}

    #[Route('/api/search', name: 'search', methods: ['POST'])]
    public function search(Request $request): JsonResponse
    {
        $form = $this->createForm(SearchGitRepositoryType::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->json($this->formErrorsChecker->getErrors($form), Response::HTTP_BAD_REQUEST);
        }

        /** @var string $phrase */
        $phrase = $form->get('phrase')->getData();
        /** @var string $repositoryName */
        $repositoryName = $form->get('repositoryName')->getData();

        $this->bus->dispatch(new SearchCommitsByText\Command(
            $repositoryName,
            $phrase
        ));

        return $this->json($this->foundCommits->foundCommitsByPhrase($phrase), Response::HTTP_OK);
    }
}
