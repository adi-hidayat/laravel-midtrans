<?php

namespace Tests\Feature;

use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use stdClass;

class OverTheCounterTest extends TestCase
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
    public function testOverTheCounterAlfamartSuccess(): void
    {
        $order = $this->getOrder('OVER_THE_COUNTER', 'CSTORE', 'ALFAMART');
        $result = $this->paymentService->chargePayment($order);

        $statusCode = 201 == $result->status_code;
        $statusMessage = 'Success, cstore transaction is successful' == $result->status_message;

        self::assertTrue($statusCode);
        self::assertTrue($statusMessage);

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
        $order->total       = 10000;
        
        // item details
        $item1              = new stdClass;
        $item1->id          = uniqid();
        $item1->name        = "Indomie Goreng";
        $item1->quantity    = 2;
        $item1->price       = 2500;

        $item2              = new stdClass;
        $item2->id          = uniqid();
        $item2->name        = "Indomie Goreng Rendang";
        $item2->quantity    = 2;
        $item2->price       = 2500;

        $order->items = [
           $item1,
           $item2
        ];

        // customer
        $customer = new stdClass;
        $customer->firstName        = "Adi";
        $customer->lastName         = "Hidayat";
        $customer->email            = "john@example.com";
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
}
