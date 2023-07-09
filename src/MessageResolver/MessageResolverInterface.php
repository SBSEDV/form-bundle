<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\MessageResolver;

use Symfony\Component\Form\FormError;

interface MessageResolverInterface
{
    /**
     * Resolve an error message.
     *
     * @param FormError $formError The FormError object to resolve from.
     *
     * @return string|null The error message or null if not supported.
     */
    public function resolveMessage(FormError $formError): ?string;
}
