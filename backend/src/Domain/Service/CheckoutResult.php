<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Money\Money;
use App\Domain\Payment\Charge;

final class CheckoutResult
{
    /** @param array<int, array{id:string, nome:string, quantidade:int, unitario:string, subtotal:string}> $items */
    public function __construct(
        public readonly array $items,
        public readonly Money $subtotal,
        public readonly Charge $charge,
        public readonly ?Money $discount = null
    ) {}
}
