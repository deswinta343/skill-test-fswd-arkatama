<?php

namespace App\Rules\Payment;

use App\Helpers\Payment\Payment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PaymentHelperIsExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $files = Payment::getAvailableHelpers();
        if (! in_array($value, $files)) {
            $fail("Helper $value tidak tersedia.");
        }
    }
}
