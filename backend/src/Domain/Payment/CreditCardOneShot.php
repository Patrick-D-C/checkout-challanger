<?php
declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Money\Money;

final class CreditCardOneShot implements PaymentMethod
{
    private const DISCOUNT = '0.10';

    public function finalize(Money $principal): Charge
    {
        $total = $principal->mul(bcsub('1', self::DISCOUNT, 4));
        return new Charge(
            total: $total,
            method: 'CARTAO_CREDITO',
            installments: 1,
            installmentValue: $total
        );
    }
}