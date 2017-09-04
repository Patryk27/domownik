<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidationFactory;

class ValidatorServiceProvider
    extends ServiceProvider {

    /**
     * @param ValidationFactory $validator
     * @return void
     */
    public function boot(ValidationFactory $validator): void {
        $this
            ->setupPastOrToday($validator)
            ->setupFutureOrToday($validator)
            ->setupGreaterThanField($validator)
            ->setupPositive($validator);
    }

    /**
     * @param ValidationFactory $validator
     * @return $this
     */
    protected function setupPastOrToday(ValidationFactory $validator) {
        /** @noinspection PhpUnusedParameterInspection */
        $validator->extend('past_or_today', function ($attribute, $value, $parameters, $validator) {
            $value = new Carbon($value);
            return $value->isPast() || $value->isToday();
        });

        return $this;
    }

    /**
     * @param ValidationFactory $validator
     * @return $this
     */
    protected function setupFutureOrToday(ValidationFactory $validator) {
        /** @noinspection PhpUnusedParameterInspection */
        $validator->extend('future_or_today', function ($attribute, $value, $parameters, $validator) {
            $value = new Carbon($value);
            return $value->isFuture() || $value->isToday();
        });

        return $this;
    }

    /**
     * @param ValidationFactory $validator
     * @return $this
     */
    protected function setupGreaterThanField(ValidationFactory $validator) {
        /** @noinspection PhpUnusedParameterInspection */
        $validator->extend('greater_than_field', function ($attribute, $value, $parameters, $validator) {
            $fieldName = $parameters[0];

            $fields = $validator->getData();
            $fieldValue = $fields[$fieldName];

            return $value > $fieldValue;
        });

        /** @noinspection PhpUnusedParameterInspection */
        $validator->replacer('greater_than_field', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });

        return $this;
    }

    /**
     * @param ValidationFactory $validator
     * @return $this
     */
    protected function setupPositive(ValidationFactory $validator) {
        /** @noinspection PhpUnusedParameterInspection */
        $validator->extend('positive', function ($attribute, $value, $parameters, $validator) {
            return $value > 0;
        });

        return $this;
    }

}
