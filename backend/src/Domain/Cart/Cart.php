<?php
declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Money\Money;

final class Cart
{
    /** @var LineItem[] */
    private array $items = [];

    public function __construct(private string $currency = 'BRL') {}

    public function addItem(LineItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Função responsavel por calcular o valor dos itens
     * Utilizando o valor 0.00 como base e adicionando o subtotal
     */
    public function total(): Money
    {
        $acc = Money::zero($this->currency);
        
        foreach ($this->items as $i) {
            $acc = $acc->add($i->subtotal());
        }

        return $acc;
    }

    /** @return LineItem[] */
    public function items(): array
    {
        return $this->items;
    }
}