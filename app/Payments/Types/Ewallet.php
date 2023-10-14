<?php 

namespace App\Payments\Types;

class Ewallet
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
     * @var string
     */
    protected string $paymentType;

    /**
     * @var string
     */
    protected string $paymentBeneficiary;

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
     * @var array
     */
    protected array $paymentTypeDetails;

    /**
     * @param object $orderDetail
     */
    public function __construct(object $orderDetail)
    {
        $this->orderDetail = $orderDetail;

        self::setPaymentMethod($this->orderDetail);
        self::setPaymentType($this->orderDetail);
        self::setPaymentBeneficiary($this->orderDetail);
        self::setTransactionDetails($this->orderDetail);
        self::setItemDetails($this->orderDetail);
        self::setCustomerDetails($this->orderDetail);
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
     * @return void
     */
    public function setPaymentType($order) : void
    {   
        $this->paymentType = strtolower($order->paymentType);
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function setPaymentBeneficiary($order) : void
    {
        $this->paymentBeneficiary = strtolower($order->paymentBeneficiary);
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
     * @return array
     */
    public function requestPaymentDetails() : array
    {
        $data = [
            "payment_type"          => $this->paymentType,
            "transaction_details"   => $this->transactionDetails,
            "item_details"          => $this->itemDetails,
            "customer_details"      => $this->customerDetails,
            $this->paymentType      => [
                "enable_callback"   => true,
                "callback_url"      => 'https://www.google.com'
            ]
        ];

        return $data;
    }
}
