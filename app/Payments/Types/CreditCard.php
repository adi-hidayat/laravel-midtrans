<?php 

namespace App\Payments\Types;

class CreditCard
{
    /**
     * @var object
     */
    protected object $orderDetail;

    /**
     * @var string
     */
    protected string $paymentMethod;

    /**
     * @var array
     */
    protected array $transactionDetails;
    
    /**
     * @var array
     */
    protected array $itemDetails;
    
    /**
     * @var array
     */
    protected array $customerDetails;

    /**
     * @var string
     */
    protected string $tokenId;

    /**
     * @param object $orderDetail
     */
    public function __construct(object $orderDetail)
    {
        $this->orderDetail = $orderDetail;

        self::setPaymentMethod($this->orderDetail);
        self::setTransactionDetails($this->orderDetail);
        self::setItemDetails($this->orderDetail);
        self::setCustomerDetails($this->orderDetail);
        self::setTokenId($this->orderDetail);
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function setPaymentMethod($order) : void
    {
        $this->paymentMethod = strtolower($order->paymentMethod);
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function setTransactionDetails($order) : void
    {
        $this->transactionDetails = [
            "order_id"              => $order->id,
            "gross_amount"          => $order->total
        ];
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function setItemDetails($order) : void
    {
        $this->itemDetails = $order->items;
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function setCustomerDetails($order) : void
    {
        $customer = $order->customer;
        $this->customerDetails = [
            "first_name"            => $customer->firstName,
            "last_name"             => $customer->lastName,
            "email"                 => $customer->email,
            "phone"                 => $customer->phoneNumber
        ];
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function setTokenId($order) : void
    {   
        $this->tokenId = $order->tokenId;
    }

    /**
     * @return array
     */
    public function requestPaymentDetails() : array
    {
        $data = [
            "payment_type"          => $this->paymentMethod,
            "transaction_details"   => $this->transactionDetails,
            "item_details"          => $this->itemDetails,
            "customer_details"      => $this->customerDetails,
            $this->paymentMethod    => [
                    'token_id'      => $this->tokenId,
                    'authentication'=> true
            ]
        ];
        
        return $data;
    }
}
