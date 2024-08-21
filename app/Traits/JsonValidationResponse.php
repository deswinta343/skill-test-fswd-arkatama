<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait JsonValidationResponse
{
    protected function failedValidation(Validator $validator)
    {
        $errors = $this->validator->errors();

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'code' => 422,
                'status' => 'Unprocessable Entity',
                'message' => 'Tidak dapat memproses permintaan karena data tidak valid',
                'errors' => $errors,
            ], 422)
        );
    }
}
