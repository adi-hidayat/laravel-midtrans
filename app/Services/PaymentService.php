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
    private string $getTokenEndpoint;

    /**
     */
    public function __construct()
    {
        $this->serverKey = Config::get('midtrans.auth.server_key');

        $this->chargeEndpoint = Config::get('midtrans.endpoints.core.charge');

        $this->getTokenEndpoint = Config::get('midtrans.endpoints.core.token');
    }
    /**
     * @param object $order
     * 
     * Charge or proccess transaction
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
     * @param object $transaction
     * 
     * Get credit card token for charge credit card
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
     * 
     * Handle notification after payment from midtrans
     * 
     * @return object
     */
    public function notifyPayment(): object
    {
        return new stdClass;
    }

    /**
     * @param object $order
     * 
     * Refund payment
     * 
     * @return object
     */
    public function refundPayment(object $order) : object
    {
        return new stdClass;
    }
}