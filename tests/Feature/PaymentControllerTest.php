<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\Rules\Unique;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testBankTransferApiSuccess(): void
    {
        $order = $this->getOrder('BANK_TRANSFER', 'VIRTUAL_ACCOUNT', 'BCA');

        $this->json('POST', '/api/v1/charge', $order)->assertSeeText('Success, Bank Transfer transaction is created');
    }

     /**
     * @param mixed $paymentMethod
     * @param mixed $paymentType
     * @param mixed $paymentBeneficiary
     * 
     * @return array
     */
    public function getOrder($paymentMethod, $paymentType = null, $paymentBeneficiary = null) : array
    {
        $jsonData = '{
            "id": "' . strtoupper(uniqid('ORDER-')) . '",
            "total": 57000000,
            "items": [
                {
                    "id": "652b3ef98a89d",
                    "name": "Iphone 15 Pro",
                    "quantity": 1,
                    "price": 27000000
                },
                {
                    "id": "652b3ef98a89e",
                    "name": "Iphone 15 Pro Max",
                    "quantity": 1,
                    "price": 30000000
                }
            ],
            "customer": {
                "firstName": "Adi",
                "lastName": "Hidayat",
                "email": "john@example.com",
                "phoneNumber": "0858410678625"
            },
            "paymentMethod": "' . $paymentMethod . '",
            "paymentType": "' . $paymentType . '",
            "paymentBeneficiary": "' . $paymentBeneficiary . '",
            "createdAt": "2023-10-15 01:23:05",
            "updatedAt": "2023-10-15 01:23:05"
        }';
        
        
        $orderData = json_decode($jsonData, true);
        
       return (array) $orderData;
    }
}
