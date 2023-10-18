<?php

namespace App\Interfaces\Snap;

interface Payment 
{
    /**
     * @param object $payment
     * 
     * @return object
     */
    public function snapTransaction(object $payment) : object;

}