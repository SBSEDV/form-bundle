<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormError;

class DoctrineTypeCauseResolver implements CauseResolverInterface
{
    public function resolveCause(FormError $formError): ?string
    {
        $type = $formError->getOrigin()?->getConfig()->getType()->getInnerType();
        $cause = $formError->getCause();

        // Incase the "multiple" option is used and an invalid id is supplied
        if (!$type instanceof DoctrineType || !$cause instanceof TransformationFailedException || !\str_starts_with($cause->getMessage(), 'The choices "')) {
            return null;
        }

        return 'invalid_choice';
    }
}
