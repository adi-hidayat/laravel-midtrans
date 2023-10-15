<?php 

namespace App\Services;

use App\Payments\PaymentRequest;
use App\Interfaces\Core\Payment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Exception;
use stdClass;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Handle bank transfer transaction
 * with Virtual account & echannel
 */
class PaymentService implements Payment
{
    /**
     * @param object $order
     * 
     * Charge or proccess transaction
     * 
     * @return object
     */
    public function chargePayment(object $order): object
    {
        $serverKey = Config::get('midtrans.auth.server_key');
        $endPoint = Config::get('midtrans.endpoints.core.charge');
        $paymentRequest = new PaymentRequest($order);
        $payload = $paymentRequest->requestPaymentDetails();

        try {

            $response = Http::withBasicAuth($serverKey, '')
                            ->withHeader('Content-type', 'application/json')
                            ->post($endPoint, $payload);

            return $response;
        
        } catch (HttpException $e) {

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

    public function refundPayment(object $order) : object
    {
        return new stdClass;
    }
}