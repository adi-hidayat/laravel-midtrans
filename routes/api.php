<?php

use App\Http\Controllers\api\v1\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function(){
    Route::post('/charge', [PaymentController::class, 'chargePayment']);
    Route::post('/nofity', [PaymentController::class, 'notifyPayment']);
});