<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use stdClass;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function chargePayment(Request $request) : JsonResponse
    {
        try {

            $rules = [
                'id'                    => 'required',
                'total'                 => 'required|numeric',
                'items'                 => 'required|array',
                'items.*.id'            => 'required',
                'items.*.name'          => 'required',
                'items.*.quantity'      => 'required|integer',
                'items.*.price'         => 'required|numeric',
                'customer'              => 'required|array',
                'customer.firstName'    => 'required',
                'customer.lastName'     => 'required',
                'customer.email'        => 'required|email',
                'customer.phoneNumber'  => 'required',
                'paymentMethod'         => 'required'
                
            ];

            if ($request->paymentMethod == 'BANK_TRANSFER') {
                $rules = array_merge($rules, [
                    'paymentBeneficiary'    => 'required',
                    'paymentType'           => 'required'
                ]);
            }

            if ($request->paymentMethod == 'CREDIT_CARD') {
                $rules = array_merge($rules, [
                    'tokenId'    => 'required'
                ]);
            }

            IF ($request->paymentMethod == 'OVER_THE_COUNTER') {

            }

            $request->validate($rules);
    
            $payload = json_decode($request->getContent());
            
            $response = $this->paymentService->chargePayment($payload);
            
            return response()->json($response, $response->status_code);

        } catch (ValidationException $validationException) {

            $response = new stdClass;
            $response->status_code = 400;
            $response->status_message = $validationException->getMessage();

            return response()->json($response, $response->status_code);
        
        }
        
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function getCreditCardToken(Request $request) : JsonResponse
    {
        try {
            $rules = [
                'cardNumber'       => 'required|numeric',
                'cardCvv'          => 'required|numeric',
                'cardExpMonth'    => 'required|numeric',
                'cardExpYear'     => 'required|numeric'
            ];

            $request->validate($rules);

            $payload = json_decode($request->getContent());

            $response = $this->paymentService->getCreditCardToken($payload);
            
            return response()->json($response, $response->status_code);

        } catch (ValidationException $validationException) {
            
            $response = new stdClass;
            $response->status_code = 400;
            $response->status_message = $validationException->getMessage();

            return response()->json($response, $response->status_code);
        
        }
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function capturePayment(Request $request) : JsonResponse
    {
        try {

            $rules = [ 
                'transactionId' => 'required',
                'grossAmount'   => 'required|numeric'
            ];

            $request->validate($rules);

            $payload = json_decode($request->getContent());

            $response = $this->paymentService->capturePayment($payload);

            return response()->json($response, $response->status_code);
        
        } catch (ValidationException $validationException) {

            $response = new stdClass;
            $response->status_code = 400;
            $response->status_message = $validationException->getMessage();

            return response()->json($response, $response->status_code);
        
        }

    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function cancelPayment(Request $request) : JsonResponse
    {
        try {

            $rules = [ 
                'transactionId' => 'required',
            ];

            $request->validate($rules);

            $payload = json_decode($request->getContent());

            $response = $this->paymentService->cancelPayment($payload);

            return response()->json($response, $response->status_code);
        
        } catch (ValidationException $validationException) {

            $response = new stdClass;
            $response->status_code = 400;
            $response->status_message = $validationException->getMessage();

            return response()->json($response, $response->status_code);
        
        }
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function expirePayment(Request $request) : JsonResponse
    {
        try {

            $rules = [ 
                'transactionId' => 'required',
            ];

            $request->validate($rules);

            $payload = json_decode($request->getContent());

            $response = $this->paymentService->expirePayment($payload);

            return response()->json($response, $response->status_code);
        
        } catch (ValidationException $validationException) {

            $response = new stdClass;
            $response->status_code = 400;
            $response->status_message = $validationException->getMessage();

            return response()->json($response, $response->status_code);
        
        }
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function refundPayment(Request $request) : JsonResponse
    {
        try {

            $rules = [ 
                'transactionId' => 'required',
                'grossAmount'   => 'required|numeric'
            ];

            $request->validate($rules);

            $payload = json_decode($request->getContent());

            $response = $this->paymentService->refundPayment($payload);

            return response()->json($response, $response->status_code);
        
        } catch (ValidationException $validationException) {

            $response = new stdClass;
            $response->status_code = 400;
            $response->status_message = $validationException->getMessage();

            return response()->json($response, $response->status_code);
        
        }
    }

    public function transactionStatus(Request $request)
    {
        try {

            $rules = [ 
                'orderIdOrTransactionId' => 'required'
            ];

            $request->validate($rules);
            
            $response = $this->paymentService->transactionStatus($request->get('orderIdOrTransactionId'));

            return response()->json($response, $response->status_code);
        
        } catch (ValidationException $validationException) {

            $response = new stdClass;
            $response->status_code = 400;
            $response->status_message = $validationException->getMessage();

            return response()->json($response, $response->status_code);
        
        }
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function notifyPayment(Request $request) : JsonResponse
    {
        try {
            
            $paymentInfo = (object) json_decode($request->getContent());
            
            $this->paymentService->notifyPayment($paymentInfo);

            return response()->json("OK", 200);
        
        } catch (ValidationException $validationException) {

            return response()->json("NOT OK", 500);
        
        }
    }
}
