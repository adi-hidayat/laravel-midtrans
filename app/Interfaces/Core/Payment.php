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
     * @return object
     */
    public function notifyPayment() : object;

    /**
     * @param object $transaction
     * 
     * @return object
     */
    public function refundPayment(object $transaction) : object;
}