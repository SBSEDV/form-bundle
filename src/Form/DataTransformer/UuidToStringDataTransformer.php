<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

/**
 * @implements DataTransformerInterface<Uuid, string>
 */
class UuidToStringDataTransformer implements DataTransformerInterface
{
    protected const NIL = '00000000-0000-0000-0000-000000000000';

    public function __construct(
        private bool $convertNilToNull = false
    ) {
    }

    public function transform(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Uuid) {
            throw new TransformationFailedException('Expected a Uuid.');
        }

        $value = $value->toRfc4122();

        if ($this->convertNilToNull && $value === self::NIL) {
            return null;
        }

        return $value;
    }

    public function reverseTransform(mixed $value): ?Uuid
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!\is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        try {
            $uuid = Uuid::fromString($value);
        } catch (\InvalidArgumentException $e) {
            throw new TransformationFailedException(\sprintf('The value "%s" is not a valid UUID.', $value), $e->getCode(), $e);
        }

        if ($uuid instanceof NilUuid && $this->convertNilToNull) {
            $uuid = null;
        }

        return $uuid;
    }
}
