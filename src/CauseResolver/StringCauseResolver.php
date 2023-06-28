<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Component\Form\FormError;

class StringCauseResolver implements CauseResolverInterface
{
    public function resolveCause(FormError $formError): ?string
    {
        if (\is_string($formError->getCause())) {
            return $formError->getCause();
        }

        return null;
    }
}
