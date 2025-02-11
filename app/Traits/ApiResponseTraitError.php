<?php

namespace App\Traits;

trait ApiResponseTraitError
{
    public function sendApiError($message= 'Data berhasil didapatkan!', $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ]);
    }
}