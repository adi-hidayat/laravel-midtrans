<?php

namespace Tests\Feature;

use App\Services\PaymentService;
use Illuminate\Support\Facades\Config;
use stdClass;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

class CreditCardTest extends TestCase
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
    public function testGetTokenSuccess(): void
    {
        $creditCard = new stdClass;
        $creditCard->cardNumber = "4811111111111114";
        $creditCard->cardCvv = "123";
        $creditCard->cardExpMonth = "12";
        $creditCard->cardExpYear = "2025";
        $creditCard->clientKey = Config::get('midtrans.auth.client_key');
        $creditCard->serverKey = Config::get('midtrans.auth.server_key');

        $result = $this->paymentService->getCreditCardToken($creditCard);

        self::assertArrayHasKey('status_code', $result->json());
        self::assertArrayHasKey('token_id', $result->json());
        self::assertArrayHasKey('hash', $result->json());
    }

    public function testChargeCreditCardSuccess()
    {
        $creditCard = new stdClass;
        $creditCard->cardNumber = "4811111111111114";
        $creditCard->cardCvv = "123";
        $creditCard->cardExpMonth = "12";
        $creditCard->cardExpYear = "2025";
        $creditCard->clientKey = Config::get('midtrans.auth.client_key');
        $creditCard->serverKey = Config::get('midtrans.auth.server_key');

        $result = $this->paymentService->getCreditCardToken($creditCard);

        $response = $result->json();

        self::assertArrayHasKey('status_code', $response);
        self::assertArrayHasKey('token_id', $response);

        $order = $this->getOrder('CREDIT_CARD', $response['token_id']);
        $result = $this->paymentService->chargePayment($order);
        $response = $result->json();
        
        $statusCode = 200 == $response['status_code'];
        $statusMessage = 'Success, Credit Card transaction is successful' == $response['status_message'];

        self::assertTrue($statusCode);
        self::assertTrue($statusMessage);
    }

    public function testCaptureCreditCard() : void
    {
        $payment = new stdClass;
        $payment->transaction_id = "77d7a5c9-2817-48ce-9b09-387337b84835";
        $payment->gross_amount = 57000000;
        $result = $this->paymentService->capturePayment($payment);
        $response = $result->json();

        $statusCode = 200 == $response['status_code'];
        $statusMessage = 'Success, Credit Card capture transaction is successful' == $response['status_message'];

        self::assertTrue($statusCode);
        self::assertTrue($statusMessage);
    }

    public function testCancelPaymentCreditCardSuccess()
    {
        $payment = new stdClass;
        $payment->orderId = 'cbb675a7-6766-415f-8a8d-2fa7c12fad2c';
        $result = $this->paymentService->cancelPayment($payment);
        $response = $result->json();
        
        $statusCode = 200 == $response['status_code'];
        $statusMessage = 'Success, transaction is canceled' == $response['status_message'];

        self::assertTrue($statusCode);
        self::assertTrue($statusMessage);

    }

    public function testRefundPaymentCreditCardSuccess()
    {
        $payment = new stdClass;
        $payment->orderId = '076bb113-0a23-4904-8630-5ad42df5fe27';
        $payment->refundKey = "REFUND_0001";
        $payment->amount = 57000000;
        $payment->reason = "Refund payment";
        $result = $this->paymentService->refundPayment($payment);
        $response = $result->json();
        
        $statusCode = 200 == $response['status_code'];
        $statusMessage = 'Success, refund request is approved' == $response['status_message'];

        self::assertTrue($statusCode);
        self::assertTrue($statusMessage);

    }

    public function testExpirePaymentCreditCardSuccess()
    {
        $payment = new stdClass;
        $payment->orderId = '9efc4f46-a842-4862-9d21-475e122af434';
        $result = $this->paymentService->expirePayment($payment);
        $response = $result->json();
        
        $statusCode = 200 == $response['status_code'];
        $statusMessage = 'Success, transaction is expired' == $response['status_message'];

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
    public function getOrder($paymentMethod, $tokenId) : object
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

        // token id
        $order->tokenId = $tokenId;

        // date
        $order->createdAt = date('Y-m-d H:i:s');

        return $order;
    }
}
