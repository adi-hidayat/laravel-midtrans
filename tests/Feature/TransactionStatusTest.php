<?php

namespace Tests\Feature;

use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionStatusTest extends TestCase
{

    private PaymentService $paymentService;

    public function setUp() : void
    {
        parent::setUp();
        $this->paymentService = $this->app->make(PaymentService::class);
    }

    /**
     * A basic feature test example.
     */
    public function testTransactionStatus(): void
    {
        $result = $this->paymentService->transactionStatus('cc7e6d37-50a3-4b86-b270-a0c8de8e9e2f');
        
        $statusCode = 200 == $result->status_code;
        self::assertTrue($statusCode);
    }
}
