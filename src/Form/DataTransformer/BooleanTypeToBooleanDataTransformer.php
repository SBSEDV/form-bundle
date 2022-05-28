<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @implements DataTransformerInterface<bool, string>
 */
class BooleanTypeToBooleanDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private array $trueValues,
        private array $falseValues
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return 'false';
        }

        if (!\is_bool($value)) {
            throw new TransformationFailedException(\sprintf('Expected type bool, found "%s".', \get_debug_type($value)));
        }

        return $value ? 'true' : 'false';
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(mixed $value): bool
    {
        if (null === $value) {
            return false;
        }

        if (\is_string($value)) {
            $value = \strtolower($value);
        }

        if (\in_array($value, $this->trueValues, true)) {
            return true;
        }

        if (\in_array($value, $this->falseValues, true)) {
            return false;
        }

        throw new TransformationFailedException(\sprintf('"%s" is not a valid boolean value.', $value));
    }
}
