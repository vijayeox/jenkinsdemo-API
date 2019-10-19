<?php
namespace Oxzion\Payment;

interface PaymentEngine
{
    public function initiatePaymentProcess($data);

    public function handleTransaction($data);
}
