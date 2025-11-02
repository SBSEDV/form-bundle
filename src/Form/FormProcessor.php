<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class FormProcessor implements FormProcessorInterface
{
    private readonly PropertyAccessorInterface $propertyAccessor;

    public function __construct(?PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }

    public function processForm(FormInterface $form, object &$object, array $ignoredKeys = []): void
    {
        /** @var FormInterface $childForm */
        foreach ($form as $childForm) {
            if (!$childForm->getConfig()->getMapped() || $childForm->isDisabled() || \in_array($childForm->getName(), $ignoredKeys, true)) {
                continue;
            }

            $propertyPath = $childForm->getPropertyPath();

            if ($propertyPath === null || $propertyPath->getLength() > 1) {
                throw new \InvalidArgumentException();
            }

            $key = $propertyPath->getElement(0);

            if ($childForm->isSubmitted() && $childForm->isValid()) {
                // @phpstan-ignore-next-line parameterByRef.type
                $this->propertyAccessor->setValue($object, $key, $childForm->getData());
            }
        }
    }
}
