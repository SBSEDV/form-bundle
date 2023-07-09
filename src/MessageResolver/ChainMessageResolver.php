<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\MessageResolver;

use Symfony\Component\Form\FormError;

class ChainMessageResolver implements MessageResolverInterface
{
    /**
     * @param iterable<MessageResolverInterface> $messageResolvers
     */
    public function __construct(
        private readonly iterable $messageResolvers
    ) {
    }

    public function resolveMessage(FormError $formError): ?string
    {
        foreach ($this->messageResolvers as $messageResolver) {
            $msg = $messageResolver->resolveMessage($formError);

            if (null !== $msg) {
                return $msg;
            }
        }

        return null;
    }
}
