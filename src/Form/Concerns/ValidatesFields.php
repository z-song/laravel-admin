<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

trait ValidatesFields
{
    /**
     * @param array $input
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function validateErrorResponse($input)
    {
        if ($message = $this->validationMessages($input)) {
            return response([
                'status'     => false,
                'validation' => $message,
                'message'    => $message->first(),
            ], 422);
        }
    }

    /**
     * Get validation messages.
     *
     * @param array $input
     *
     * @return MessageBag|bool
     */
    public function validationMessages($input)
    {
        if ($this->fields()->isEmpty()) {
            return false;
        }

        $failedValidators = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            if (!$validator = $field->getValidator($input)) {
                continue;
            }

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);

        return $message->any() ? $message : false;
    }

    /**
     * Merge validation messages from input validators.
     *
     * @param \Illuminate\Validation\Validator[] $validators
     *
     * @return MessageBag
     */
    protected function mergeValidationMessages($validators): MessageBag
    {
        $messageBag = new MessageBag();

        foreach ($validators as $validator) {
            $messageBag = $messageBag->merge($validator->messages());
        }

        return $messageBag;
    }
}
