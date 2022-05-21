<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\ParamResolver;

use Symfony\Component\Form\FormError;

interface ParamResolverInterface
{
    /**
     * Resolve the parameter that contains the error.
     *
     * @param FormError $formError The FormError object.
     *
     * @return string|null The parameter key or null if not supported.
     */
    public function resolveParam(FormError $formError): ?string;
}
