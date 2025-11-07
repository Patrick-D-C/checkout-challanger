<?php
declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Money\Money;

interface PaymentMethod
{
    public function finalize(Money $principal): Charge;
}