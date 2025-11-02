<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @implements DataTransformerInterface<bool|null, string>
 */
class BooleanTypeToBooleanDataTransformer implements DataTransformerInterface
{
    /**
     * @param array<int|string|bool> $trueValues
     * @param array<int|string|bool> $falseValues
     */
    public function __construct(
        private readonly array $trueValues,
        private readonly array $falseValues,
        private readonly ?bool $default = null,
    ) {
    }

    public function transform(mixed $value): string
    {
        if (null === $value) {
            if (null === $this->default) {
                return '';
            }

            return $this->default ? 'true' : 'false';
        }

        if (!\is_bool($value)) { // @phpstan-ignore function.alreadyNarrowedType
            throw new TransformationFailedException(\sprintf('Expected type bool, found "%s".', \get_debug_type($value)));
        }

        return $value ? 'true' : 'false';
    }

    public function reverseTransform(mixed $value): ?bool
    {
        if (null === $value) {
            return $this->default;
        }

        if (\is_string($value)) { // @phpstan-ignore function.alreadyNarrowedType
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
