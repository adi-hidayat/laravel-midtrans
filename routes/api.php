<?php

use App\Http\Controllers\api\v1\PaymentController;
use App\Http\Middleware\PaymentServiceMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([PaymentServiceMiddleware::class])->prefix('/v1/payment')->group(function(){
    Route::post('/charge', [PaymentController::class, 'chargePayment']);
    Route::post('/token', [PaymentController::class, 'getCreditCardToken']);
    Route::post('/capture', [PaymentController::class, 'capturePayment']);
    Route::post('/cancel', [PaymentController::class, 'cancelPayment']);
    Route::post('/refund', [PaymentController::class, 'refundPayment']);
    Route::get('/status', [PaymentController::class, 'transactionStatus']);
    Route::post('/nofity', [PaymentController::class, 'notifyPayment']);
});