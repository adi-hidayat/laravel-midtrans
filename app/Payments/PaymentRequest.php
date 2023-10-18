<?php

namespace App\Payments;

use App\Payments\Types\BankTransfer;
use App\Payments\Types\CardlessCredit;
use App\Payments\Types\CreditCard;
use App\Payments\Types\Ewallet;
use App\Payments\Types\OverTheCounter;
use App\Payments\Types\SnapTransaction;
use stdClass;

class PaymentRequest {

    protected object $order;

    public function __construct(object $order)
    {
        $this->order = $order;
    }

    /**
     * format payload
     * 
     * @return array
     */
    public function requestPaymentDetails() : array
    {
        $paymentMethod = $this->order->paymentMethod;
        
        $paymentDetails = [];

        switch ($paymentMethod) {
            case 'BANK_TRANSFER' :
                $payment = new BankTransfer($this->order);
                break;

            case 'CREDIT_CARD' : 
                $payment = new CreditCard($this->order);
                break;

            case 'EWALLET' :
                $payment = new Ewallet($this->order);
                break;

            case 'OVER_THE_COUNTER' :
                $payment = new OverTheCounter($this->order);
                break;

            case 'CARDLESS_CREDIT' :
                $payment = new CardlessCredit($this->order);
                break;
            
            case 'ONLINE_PAYMENT' :
                $payment = new SnapTransaction($this->order);
                break;
        }

        return $payment->requestPaymentDetails();
    }
}