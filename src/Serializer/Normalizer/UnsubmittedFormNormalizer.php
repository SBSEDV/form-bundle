<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Serializer\Normalizer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UnsubmittedFormNormalizer implements NormalizerInterface
{
    /** The value will be used as the normalized "message" key (e.g. "msg"). */
    public const CONTEXT_MESSAGE_KEY = 'unsubmitted_form_error.message_key';
    /** The value will be used as the normalized "type" key */
    public const CONTEXT_TYPE_KEY = 'unsubmitted_form_error.type_key';
    /** The value that will appear under the "type" key. */
    public const CONTEXT_ERROR_TYPE = 'unsubmitted_form_error.type';

    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param FormInterface $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $messageKey = $context[self::CONTEXT_MESSAGE_KEY] ?? 'message';
        $typeKey = $context[self::CONTEXT_TYPE_KEY] ?? 'type';

        return [[
            // @phpstan-ignore-next-line array.invalidKey
            $messageKey => $this->translator->trans('request_body_is_empty', domain: 'sbsedv_form'),
            $typeKey => $context[self::CONTEXT_ERROR_TYPE] ?? 'invalid_request_error',
        ]];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FormInterface && !$data->isSubmitted();
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            FormInterface::class => self::class === static::class,
        ];
    }
}
