<?php

namespace Tests\Feature;

use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use stdClass;
use Tests\TestCase;

use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\assertTrue;

class BankTransferTest extends TestCase
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
    public function testLoadPaymentService(): void
    {
        assertTrue(true);
    }

    /**
     * @return void
     */
    public function testVirtucalAccountSuccess() : void
    {
        
        $result = $this->paymentService->chargePayment($this->getOrder('BANK_TRANSFER', 'VIRTUAL_ACCOUNT', 'BCA'));

        $statusCodeSuccess = 201 == $result->status_code;
        assertTrue($statusCodeSuccess);

        $messageMessage = 'Success, Bank Transfer transaction is created' == $result->status_message;
        assertTrue($messageMessage);
    }

    /**
     * @return void
     */
    public function testEchannelSuccess() : void
    {
        $result = $this->paymentService->chargePayment($this->getOrder('BANK_TRANSFER', 'ECHANNEL', 'MANDIRI'));
        
        $statusCodeSuccess = 201 == $result->status_code;
        assertTrue($statusCodeSuccess);

        $messageMessage = 'OK, Mandiri Bill transaction is successful' == $result->status_message;
        assertTrue($messageMessage);
    }

    /**
     * @return void
     */
    public function testPermataSuccess() : void
    {
        $result = $this->paymentService->chargePayment($this->getOrder('BANK_TRANSFER'));
        $result = $result->json();

        $statusCodeSuccess = 201 == $result->status_code;
        assertTrue($statusCodeSuccess);

        $messageMessage = 'Success, PERMATA VA transaction is successful' == $result->status_message;
        assertTrue($messageMessage);
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
