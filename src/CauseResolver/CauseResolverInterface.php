<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Component\Form\FormError;

interface CauseResolverInterface
{
    /**
     * Resolve an error cause string (error code).
     *
     * This should ideally depend on `$formError->getCause()`.
     *
     * @param FormError $formError The FormError object to resolve from.
     *
     * @return string|null The error cause or null if not supported.
     */
    public function resolveCause(FormError $formError): ?string;
}
