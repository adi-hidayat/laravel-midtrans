<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use stdClass;

class PaymentController extends Controller
{

    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    public function snapTransaction()
    {
        $response = $this->paymentService->snapTransaction($this->getOrder());
       
        return redirect($response->redirect_url);
    }

    /**
     * @param mixed $paymentMethod
     * @param mixed $paymentType
     * @param mixed $paymentBeneficiary
     * 
     * @return object
     */
    public function getOrder() : object
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
        $customer->phoneNumber      = "123123123123";
        $order->customer            = $customer;

        // payment method
        $order->paymentMethod       = 'ONLINE_PAYMENT';
        $order->paymentBeneficiary  = 'MIDTRANS'; // optional
        $order->paymentType         = 'SNAP'; // something to flagged payment and this is optional

        // date
        $order->createdAt = date('Y-m-d H:i:s');

        return $order;
    }
}
