<?php

namespace App\Traits;

trait ApiResponseTraitSuccess
{
    public function sendApiResponse($message = 'Permintaan tidak valid!', $data = null, $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'statusCode' => $code
        ], $code);
    }
}