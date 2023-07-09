<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\MessageResolver;

use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormError;

class DoctrineTypeMessageResolver implements MessageResolverInterface
{
    public function resolveMessage(FormError $formError): ?string
    {
        $type = $formError->getOrigin()?->getConfig()->getType()->getInnerType();
        $cause = $formError->getCause();

        // Incase the "multiple" option is used and an invalid id is supplied
        if (!$type instanceof DoctrineType || !$cause instanceof TransformationFailedException || !\str_starts_with($cause->getMessage(), 'The choices "')) {
            return null;
        }

        // Currently this will always be NULL, someday symfony will maybe add translations for that error
        if (null !== $cause->getInvalidMessage()) {
            return \strtr($cause->getInvalidMessage(), $cause->getInvalidMessageParameters());
        }

        return $cause->getMessage();
    }
}
