<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

final class SymfonyFormErrorsChecker
{
    /**
     * @return array<string, array<string>>
     */
    public function getErrors(FormInterface $form): array
    {
        $errors = [];
        /**
         * @var FormError $error
         */
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }
        /**
         * @var FormInterface $child
         */
        foreach ($form as $child) {
            if (!$child->isValid()) {
                /**
                 * @var FormError $error
                 */
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}
