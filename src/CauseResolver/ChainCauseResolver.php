<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Component\Form\FormError;

class ChainCauseResolver implements CauseResolverInterface
{
    /**
     * @param iterable<CauseResolverInterface> $causeResolvers
     */
    public function __construct(
        private readonly iterable $causeResolvers,
    ) {
    }

    public function resolveCause(FormError $formError): ?string
    {
        foreach ($this->causeResolvers as $causeResolver) {
            $cause = $causeResolver->resolveCause($formError);

            if (null !== $cause) {
                return $cause;
            }
        }

        return null;
    }
}
