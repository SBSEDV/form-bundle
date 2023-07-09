<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Serializer\Normalizer;

use SBSEDV\Bundle\FormBundle\CauseResolver\CauseResolverInterface;
use SBSEDV\Bundle\FormBundle\MessageResolver\MessageResolverInterface;
use SBSEDV\Bundle\FormBundle\ParamResolver\ParamResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormErrorNormalizer implements NormalizerInterface
{
    /** The value will be used as the normalized "message" key (e.g. "msg"). */
    public const CONTEXT_MESSAGE_KEY = 'form_error.message_key';
    /** The value will be used as the normalized "param" key (e.g. "key") */
    public const CONTEXT_PARAMETER_KEY = 'form_error.parameter_key';
    /** The value will be used as the normalized "cause" key (e.g. "code") */
    public const CONTEXT_CAUSE_KEY = 'form_error.cause_key';
    /** The value will be used as the normalized "type" key */
    public const CONTEXT_TYPE_KEY = 'form_error.type_key';
    /** The value that will appear under the "type" key. */
    public const CONTEXT_ERROR_TYPE = 'form_error.type';

    public function __construct(
        private readonly CauseResolverInterface $causeResolver,
        private readonly ParamResolverInterface $paramResolver,
        private readonly MessageResolverInterface $messageResolver
    ) {
    }

    /**
     * @param FormInterface $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $errors = [];

        $messageKey = $context[self::CONTEXT_MESSAGE_KEY] ?? 'message';
        $parameterKey = $context[self::CONTEXT_PARAMETER_KEY] ?? 'param';
        $causeKey = $context[self::CONTEXT_CAUSE_KEY] ?? 'cause';
        $typeKey = $context[self::CONTEXT_TYPE_KEY] ?? 'type';

        $errorType = $context[self::CONTEXT_ERROR_TYPE] ?? 'invalid_request_error';

        /** @var FormError $formError */
        foreach ($object->getErrors(true) as $formError) {
            $origin = $formError->getOrigin();

            if (null === $origin) {
                // this should NEVER happen
                continue;
            }

            $error = [
                $messageKey => $this->messageResolver->resolveMessage($formError) ?? $formError->getMessage(),
                $typeKey => $errorType,
            ];

            $param = $this->paramResolver->resolveParam($formError);
            if (!empty($param)) {
                $error[$parameterKey] = $param;
            }

            $cause = $this->causeResolver->resolveCause($formError);
            if (!empty($cause)) {
                $error[$causeKey] = \strtolower($cause);
            }

            $errors[] = $error;
        }

        return $errors;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof FormInterface && $data->isSubmitted() && !$data->isValid();
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            FormInterface::class => __CLASS__ === static::class,
        ];
    }
}
