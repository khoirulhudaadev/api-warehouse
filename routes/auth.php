<?php

Route::get('v1/private/testing-api', fn() => response()->json([
    'message' => 'Successfully test!'
]));