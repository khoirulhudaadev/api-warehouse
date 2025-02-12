<?php

namespace App\Traits;

trait ApiResponseTraitError
{
    public function sendApiError($message= 'Data berhasil didapatkan!', $data = null, $code = 200)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
            'statusCode' => $code
        ], $code);
    }
}