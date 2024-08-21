<?php

use Illuminate\Http\JsonResponse;

class ResponseFormatter
{
    public static function created(string $message, mixed $responsePayloads = null): JsonResponse
    {
        $response['success'] = true;
        $response['message']['title'] = 'Success';
        $response['message']['body'] = $message;
        $response['data'] = $responsePayloads;

        return response()->json($response, 201);
    }

    public static function success(string $message, mixed $responsePayloads = null, int $code = 200): JsonResponse
    {
        $response['success'] = true;
        $response['message']['title'] = 'Success';
        $response['message']['body'] = $message;
        $response['data'] = $responsePayloads;

        return response()->json($response, $code);
    }

    public static function error(string $message, mixed $responsePayloads = null, int $code = 400)
    {
        $response['success'] = false;
        $response['message']['title'] = 'Failed';
        $response['message']['body'] = $message;
        $response['data'] = $responsePayloads;

        return response()->json($response, $code);
    }
}
