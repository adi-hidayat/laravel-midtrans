<?php 

namespace App\Interfaces\Core;

interface Payment 
{
    /**
     * @param object $transaction
     * 
     * @return object
     */
    public function chargePayment(object $transaction) : object; 

    /**
     * @param object $payment
     * 
     * @return object
     */
    public function capturePayment(object $payment) : object;

    /**
     * @param object $payment
     * 
     * @return object
     */
    public function cancelPayment(object $payment) : object;

    /**
     * @param object $payment
     * 
     * @return object
     */
    public function expirePayment(object $payment) : object;

    /**
     * @param object $transaction
     * 
     * @return object
     */
    public function refundPayment(object $transaction) : object;

    /**
     * @param object $transaction
     * 
     * @return object
     */
    public function getCreditCardToken(object $transaction) : object;

    /**
     * @param string $orderIdOrTrransactionId
     * 
     * @return object
     */
    public function transactionStatus(string $orderIdOrTrransactionId) : object;

    /**
     * @return object
     */
    public function notifyPayment() : object;
}