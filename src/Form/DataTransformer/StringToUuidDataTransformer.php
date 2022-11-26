<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Uid\Uuid;

/**
 * @implements DataTransformerInterface<string, Uuid>
 */
class StringToUuidDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(mixed $value): ?Uuid
    {
        if (null === $value) {
            return $value;
        }

        try {
            $value = Uuid::fromString($value);
        } catch (\Throwable $e) {
            throw new TransformationFailedException(previous: $e);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(mixed $value): ?string
    {
        if (null === $value) {
            return $value;
        }

        if (!$value instanceof Uuid) {
            throw new TransformationFailedException('Expected type Uuid, found '.\get_debug_type($value));
        }

        return $value->toRfc4122();
    }
}
