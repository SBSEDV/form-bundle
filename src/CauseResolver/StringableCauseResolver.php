<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Component\Form\FormError;

class StringableCauseResolver implements CauseResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveCause(FormError $formError): ?string
    {
        $cause = $formError->getCause();

        if ($cause instanceof \Stringable) {
            return (string) $cause;
        }

        return null;
    }
}
