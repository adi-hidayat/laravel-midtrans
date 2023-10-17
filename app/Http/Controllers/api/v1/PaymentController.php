<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
                'paymentMethod'         => 'required',
                'paymentBeneficiary'    => 'required',
                'paymentType'           => 'required',
            ];

            $payload = $request->validate($rules);
    
            $payload = json_decode($request->getContent());
            
            $result = $this->paymentService->chargePayment($payload);
            
            if (isset($result->error)) {
                $response = $result;
            } else {
                $response = $result->json();
            }

            return response()->json($response);

        } catch (ValidationException $validationException) {

            return response()->json($validationException, 401);
        
        }
        
    }

    public function getCreditCardToken(Request $request) : JsonResponse
    {
        return response()->json([]);
    }

    public function capturePayment(Request $request) : JsonResponse
    {
        return response()->json([]);
    }

    public function cancelPayment(Request $request) : JsonResponse
    {
        return response()->json([]);
    }

    public function expirePayment(Request $request) : JsonResponse
    {
        return response()->json([]);
    }

    public function refundPayment(Request $request) : JsonResponse
    {
        return response()->json([]);
    }

    public function notifyPayment(Request $request) : JsonResponse
    {
        return response()->json([]);
    }
}
