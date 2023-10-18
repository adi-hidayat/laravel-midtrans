<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentServiceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isSecuredRequest = Config::get('midtrans.auth.secured_request');
        $configXPaymentToken = Config::get('midtrans.auth.x_payment_token');

        if ($isSecuredRequest) {
            $headerXPaymentToken = $request->header('X-PAYMENT-TOKEN');
            if ($configXPaymentToken !== $headerXPaymentToken) {
                return response([
                    'status_code' => 401, 
                    'status_message' => 'Client request unauthorized'
                    ],
                    401
                );
            }
        }

        // save log into payment_request.log
        $slackLogger = Log::channel('file');
        $slackLogger->info('Request For Payment', ["Transaction" => $request->getContent()]);

        return $next($request);
    }
}
