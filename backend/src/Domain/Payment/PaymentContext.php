<?php
declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Money\Money;

final class PaymentContext
{
    public function __construct(private PaymentMethod $method) {}

    public function charge(Money $principal): Charge
    {
        return $this->method->finalize($principal);
    }
}