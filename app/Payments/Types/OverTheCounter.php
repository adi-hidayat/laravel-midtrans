<?php 

namespace App\Payments\Types;

class OverTheCounter
{
    /**
     * @var object
     */
    protected object $orderDetail;

    /**
     * @var string
     */
    protected string $paymentType; // CONVENIENCE_STORE

    /**
     * @var string
     */
    protected string $paymentBeneficiary; // INDOMART / ALFAMART

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
    protected array $paymentTypeParams;

    /**
     * @param object $orderDetail
     */
    public function __construct(object $orderDetail)
    {
        $this->orderDetail = $orderDetail;

        self::setPaymentType($this->orderDetail);
        self::setPaymentBeneficiary($this->orderDetail);
        self::setTransactionDetails($this->orderDetail);
        self::setItemDetails($this->orderDetail);
        self::setCustomerDetails($this->orderDetail);
        self::setpaymentTypeParams($this->orderDetail);
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

    public function setpaymentTypeParams()
    {
        $this->paymentTypeParams = [
            'store'     => ucfirst($this->paymentBeneficiary),
            'message'   => "Payment via indomart for order #" . $this->orderDetail->id
        ];

        if (strtolower($this->paymentBeneficiary) == 'alfamart') {
            $this->paymentTypeParams = [
                "store"                 => ucfirst($this->paymentBeneficiary),
                "alfamart_free_text_1"  => "Thanks for shopping with us!,",
                "alfamart_free_text_2"  => "Like us on our Facebook page,",
                "alfamart_free_text_3"  => "and get 10% discount on your next purchase."
            ];
        }
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
            $this->paymentType      => $this->paymentTypeParams
        ];
        
        return $data;
    }
}
