<?php

use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\AuthWarehouseController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomThrottle;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Support\Facades\Route;

/**
 * Maximum 3 API hits within 1 minute.
 * Rate-limiting is used to prevent server overload and mitigate DDoS attacks.
 *
*/

Route::middleware([JWTMiddleware::class, CustomThrottle::class])
->prefix('v1')
->group(function() 
{
    
    // Authentications
    Route::post('/login', [AuthWarehouseController::class, 'login'])
    ->withoutMiddleware([JWTMiddleware::class, 'throttle']);
    Route::post('/forgot-password', [AuthWarehouseController::class, 'forgotPassword'])
    ->withoutMiddleware([JWTMiddleware::class, 'throttle']);
    Route::post('/reset-password', [AuthWarehouseController::class, 'resetPassword'])
    ->withoutMiddleware([JWTMiddleware::class, 'throttle']);
   
    Route::get('/private/testing-api2', fn() => response()->json([
        'message' => 'Successfully test2222!'
    ]));
    
    // Items
    Route::get('item', [ItemController::class, 'index']);
    Route::post('item', [ItemController::class, 'store']);
    Route::get('item/{id}', [ItemController::class, 'show']);
    Route::post('item/{id}', [ItemController::class, 'update']);
    Route::post('item/out/{id}', [ItemController::class, 'updateOut']);
    Route::delete('item/{id}', [ItemController::class, 'destroy']);
  
    // Deliveries
    Route::get('delivery', [DeliveryController::class, 'index']);
    Route::post('delivery/restore/{id}', [DeliveryController::class, 'restore']);
    Route::get('delivery/{id}', [DeliveryController::class, 'show']);
    Route::delete('delivery/{id}', action: [DeliveryController::class, 'destroy']);
    
    // Units
    Route::get('unit', [UnitController::class, 'index']);
    Route::post('unit', [UnitController::class, 'store']);
    Route::get('unit/{id}', [UnitController::class, 'show']);
    Route::post('unit/{id}', [UnitController::class, 'update']);
    Route::delete('unit/{id}', [UnitController::class, 'destroy']);
    
    // Types
    Route::get('type', [TypeController::class, 'index']);
    Route::post('type', [TypeController::class, 'store']);
    Route::get('type/{id}', [TypeController::class, 'show']);
    Route::post('type/{id}', [TypeController::class, 'update']);
    Route::delete('type/{id}', [TypeController::class, 'destroy']);

    // Users
    Route::get('user', [UserController::class, 'index']);
    Route::post('user', [UserController::class, 'store']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::post('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    // Roles
    Route::get('role', [RoleController::class, 'index']);
    Route::post('role', [RoleController::class, 'store']);
    Route::get('role/{id}', [RoleController::class, 'show']);
    Route::post('role/{id}', [RoleController::class, 'update']);
    Route::delete('role/{id}', [RoleController::class, 'destroy']);
});

Route::post('v1/private/user', [UserController::class, 'store']);
Route::get('v1/private/testing-api', fn() => response()->json([
    'message' => 'Successfully test!'
]));