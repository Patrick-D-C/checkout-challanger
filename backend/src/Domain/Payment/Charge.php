<?php
declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Money\Money;

final class Charge
{
    public function __construct(
        public readonly Money $total,
        public readonly string $method,
        public readonly int $installments,
        public readonly ?Money $installmentValue
    ) {}
}