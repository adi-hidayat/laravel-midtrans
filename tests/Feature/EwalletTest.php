<?php

namespace Tests\Feature;

use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use stdClass;

class EwalletTest extends TestCase
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
    public function testLoadPaymentServiceEwallet(): void
    {
        self::assertTrue(true);
    }

    /**
     * @param mixed $paymentMethod
     * @param mixed $paymentType
     * @param mixed $paymentBeneficiary
     * 
     * @return object
     */
    public function getOrder($paymentMethod, $paymentType = null, $paymentBeneficiary = null) : object
    {
        // init object order
        $order = new stdClass;

        // details
        $order->id          = uniqid();
        $order->total       = 57000000;
        
        // item details
        $item1              = new stdClass;
        $item1->id          = uniqid();
        $item1->name        = "Iphone 15 Pro";
        $item1->quantity    = 1;
        $item1->price       = 27000000;

        $item2              = new stdClass;
        $item2->id          = uniqid();
        $item2->name        = "Iphone 15 Pro Max";
        $item2->quantity    = 1;
        $item2->price       = 30000000;

        $order->items = [
           $item1,
           $item2
        ];

        // customer
        $customer = new stdClass;
        $customer->firstName        = "Adi";
        $customer->lastName         = "Hidayat";
        $customer->email            = "adihidayat.lpg@gmail.com";
        $customer->phoneNumber      = "0858410678625";
        $order->customer            = $customer;

        // payment method
        $order->paymentMethod       = $paymentMethod;
        $order->paymentBeneficiary  = $paymentBeneficiary;
        $order->paymentType         = $paymentType;

        // date
        $order->createdAt = date('Y-m-d H:i:s');

        return $order;
    }

    /**
     * @return void
     */
    public function testEwalletSuccess() : void
    {
        
        $result = $this->paymentService->chargePayment($this->getOrder('EWALLET', 'GOPAY', 'GOPAY'));
        $result = $result->json();
        
        $statusCodeSuccess = 201 == $result['status_code'];
        self::assertTrue($statusCodeSuccess);

        $messageMessage = 'GoPay transaction is created' == $result['status_message'];
        self::assertTrue($messageMessage);
    }
}
