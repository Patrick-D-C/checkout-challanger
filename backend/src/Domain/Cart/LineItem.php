<?php
declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Money\Money;

final class LineItem
{
    public function __construct(
        private string $name,
        private Money $unitPrice,
        private int $quantity
    ) {
        if ($quantity < 1) throw new \InvalidArgumentException('Quantidade inválida');
    }

    public function subtotal(): Money
    {
        return $this->unitPrice->mul((string)$this->quantity);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function unitPrice(): Money
    {
        return $this->unitPrice;
    
    }
    public function quantity(): int
    {
        return $this->quantity; 
    }
}