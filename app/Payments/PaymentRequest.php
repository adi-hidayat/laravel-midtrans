<?php

namespace App\Payments;

use App\Payments\Types\BankTransfer;
use App\Payments\Types\CardlessCredit;
use App\Payments\Types\CreditCard;
use App\Payments\Types\Ewallet;
use App\Payments\Types\OverTheCounter;
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
                $paymentDetails = $payment->requestPaymentDetails();
                break;

            case 'CREDIT_CARD' : 
                $payment = new CreditCard($this->order);
                $paymentDetails = $payment->requestPaymentDetails();
                break;

            case 'EWALLET' :
                $payment = new Ewallet($this->order);
                $paymentDetails = $payment->requestPaymentDetails();
                break;

            case 'OVER_THE_COUNTER' :
                $payment = new OverTheCounter($this->order);
                $paymentDetails = $payment->requestPaymentDetails();
                break;

            case 'CARDLESS_CREDIT' :
                $payment = new CardlessCredit($this->order);
                $paymentDetails = $payment->requestPaymentDetails();
                break;
        }

        return $paymentDetails;
    }
}