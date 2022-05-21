<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Csrf\CsrfToken;

class CsrfTokenCauseResolver implements CauseResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveCause(FormError $formError): ?string
    {
        $cause = $formError->getCause();

        if (!$cause instanceof CsrfToken) {
            return null;
        }

        return 'invalid_csrf';
    }
}
