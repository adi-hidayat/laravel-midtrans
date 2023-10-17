<?php 

namespace App\Services;

use App\Payments\PaymentRequest;
use App\Interfaces\Core\Payment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use stdClass;

/**
 * Handle bank transfer transaction
 * with Virtual account & echannel
 */
class PaymentService implements Payment
{
    /**
     * @var string
     */
    private string $serverKey;

    /**
     * @var string
     */
    private string $chargeEndpoint;

    /**
     * @var string
     */
    private string $captureEndpoint;

    /**
     * @var string
     */
    private string $cancelEndpoint;

    /**
     * @var string
     */
    private string $expireEndpoint;

    /**
     * @var string
     */
    private string $refundEndpoint;

    /**
     * @var string
     */
    private string $statusEndpoint;

    /**
     * @var string
     */
    private string $getTokenEndpoint;

    /**
     */
    public function __construct()
    {
        $this->serverKey        = Config::get('midtrans.auth.server_key');
        $this->chargeEndpoint   = Config::get('midtrans.endpoints.core.charge');
        $this->captureEndpoint  = Config::get('midtrans.endpoints.core.capture'); 
        $this->cancelEndpoint   = Config::get('midtrans.endpoints.core.cancel'); 
        $this->expireEndpoint   = Config::get('midtrans.endpoints.core.expire'); 
        $this->refundEndpoint   = Config::get('midtrans.endpoints.core.refund'); 
        $this->getTokenEndpoint = Config::get('midtrans.endpoints.core.token');
        $this->statusEndpoint   = Config::get('midtrans.endpoints.core.status');
    }
    /**
     * @param object $order
     * 
     * Charge transaction or payment
     * 
     * https://docs.midtrans.com/reference/charge-transactions-1
     * 
     * @return object
     */
    public function chargePayment(object $order): object
    {
        $paymentRequest = new PaymentRequest($order);
        $payload = $paymentRequest->requestPaymentDetails();

        try {

            $response = Http::withBasicAuth($this->serverKey, '')
                            ->withHeader('Content-type', 'application/json')
                            ->post($this->chargeEndpoint, $payload);

            return $response;
        
        } catch (HttpException $e) {

            $error = new stdClass;
            $error->error = true;
            $error->error_message = $e->getMessage();
            
            return $error;
        
        }
    }

    /**
     * @param object $payment
     * 
     * Capture transaction is triggered to capture the transaction balance when transaction_status:authorize. 
     * This is only available after Pre-Authorized Credit Card or Pre-Authorized GoPay.
     * 
     * https://docs.midtrans.com/reference/capture-transaction
     * 
     * @return object
     */
    public function capturePayment(object $payment): object
    {
        $payload = collect($payment)->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->all();
        
        try {

            $response = Http::withBasicAuth($this->serverKey, '')
                            ->withHeader('Content-type', 'application/json')
                            ->withHeader('accept', 'application/json')
                            ->post($this->captureEndpoint, $payload);

            return $response;

        } catch (HttpException $e) {

            $error = new stdClass;
            $error->error = true;
            $error->error_message = $e->getMessage();
            
            return $error;
        }
    }

    /**
     * @param object $payment
     * 
     * Card payment can be voided with Cancel method if the transaction has not been settled. 
     * The time interval during which the pre-authorized transaction can be cancelled depends on the Acquiring Bank.
     * 
     * https://docs.midtrans.com/reference/cancel-transaction
     * 
     * @return object
     */
    public function cancelPayment(object $payment): object
    {
        $this->cancelEndpoint = Str::replace('{transactionId_or_orderId}', $payment->orderId, $this->cancelEndpoint);

        try {

            $response = Http::withBasicAuth($this->serverKey, '')
                            ->withHeader('accept', 'application/json')
                            ->post($this->cancelEndpoint);
            
            return $response;

        } catch (HttpException $e) {
            
            $error = new stdClass;
            $error->error = true;
            $error->error_message = $e->getMessage();
            
            return $error;
        
        }
    }

    /**
     * @param object $payment
     * 
     * Expire transaction is triggered to update the transaction_status to expire, when the customer fails to complete the payment. 
     * The expired order_id can be reused for the same or different payment methods.
     * 
     * https://docs.midtrans.com/reference/expire-transaction
     * 
     * @return object
     */
    public function expirePayment(object $payment): object
    {
        $this->expireEndpoint = Str::replace('{transactionId_or_orderId}', $payment->orderId, $this->expireEndpoint);

        try {

            $response = Http::withBasicAuth($this->serverKey, '')
                            ->withHeader('accept', 'application/json')
                            ->post($this->expireEndpoint);
            
            return $response;

        } catch (HttpException $e) {
            
            $error = new stdClass;
            $error->error = true;
            $error->error_message = $e->getMessage();
            
            return $error;
        
        }
    }

    /**
     * @param object $order
     * 
     * Refund transaction is called to reverse the money back to customers for transactions with payment status Settlement. 
     * If transaction's status is still Pending Authorize or Capture please use Cancel API instead. The same refund_id cannot be reused.
     * 
     * Refund transaction is supported only for credit_card , gopay, shopeepay and QRIS payment methods.
     * 
     * With Refund, refund request is made to Midtrans where Midtrans will then forward it to payment providers.
     * 
     * https://docs.midtrans.com/reference/refund-transaction
     * 
     * @return object
     */
    public function refundPayment(object $payment) : object
    {
        $this->refundEndpoint = Str::replace('{transactionId_or_orderId}', $payment->orderId, $this->refundEndpoint);
        unset($payment->orderId);

        $payload = collect($payment)->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->all();

        try {

            $response = Http::withBasicAuth($this->serverKey, '')
                            ->withHeader('accept', 'application/json')
                            ->post($this->refundEndpoint, $payload);
            
            return $response;

        } catch (HttpException $e) {
            
            $error = new stdClass;
            $error->error = true;
            $error->error_message = $e->getMessage();
            
            return $error;
        
        }
    }

    /**
     * @param object $transaction
     * 
     * Token ID is a unique value that is associated with the customer’s credit card information during a transaction. 
     * The GET Token method sends the credit card information via Midtrans.min.js to Midtrans server and returns the Token ID to you.
     * 
     * https://docs.midtrans.com/reference/get-token
     * 
     * @return object
     */
    public function getCreditCardToken(object $credit_card): object
    {
        $queryParameters = collect($credit_card)->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->all();

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                            ->withHeader('Conten-type', 'application/json')
                            ->withQueryParameters($queryParameters)
                            ->get($this->getTokenEndpoint);
            
            return $response;

        } catch(HttpException $e) {
            $error = new stdClass;
            $error->error = true;
            $error->error_message = $e->getMessage();
            
            return $error;
        }
    }

    /**
     * @param string $orderIdOrTrransactionId
     * 
     * Get Transaction Status is triggered to obtain the transaction_status and other details of a specific transaction. 
     * Get Status API can be used by both Snap and Core API integration
     * https://docs.midtrans.com/reference/get-transaction-status
     * 
     * @return object
     */
    public function transactionStatus(string $orderIdOrTrransactionId): object
    {
        $this->statusEndpoint = Str::replace('{transactionId_or_orderId}', $orderIdOrTrransactionId, $this->statusEndpoint);
        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                            ->withHeader('Content-type', 'application/json')
                            ->get($this->statusEndpoint);

            return $response;
        } catch(HttpException $e){

            $error = new stdClass;
            $error->error = true;
            $error->error_message = $e->getMessage();
            
            return $error;
        }
    }

    /**
     * 
     * Handle notification after payment from midtrans
     * 
     * @return object
     */
    public function notifyPayment(): object
    {
        return new stdClass;
    }
}