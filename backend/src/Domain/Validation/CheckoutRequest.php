<?php
declare(strict_types=1);

namespace App\Domain\Validation;

/**
 * Definição de contrato claro, barreira para fraudes
 */
final class CheckoutRequest
{
    /** @param array{ id:string, quantidade:int }[] $itens */
    public function __construct(
        public array $itens,
        public string $metodo_pagamento,
        public ?int $parcelas
    ) {}

    public function validate(): void
    {
        if (empty($this->itens)) {
            throw new \InvalidArgumentException('Carrinho vazio');
        }
        foreach ($this->itens as $i) {
            if (!isset($i['id'],$i['quantidade'])) {
                throw new \InvalidArgumentException('Item inválido');
            }
            if (!is_string($i['id']) || $i['id'] === '' || $i['quantidade'] < 1) {
                throw new \InvalidArgumentException('Produto/quantidade inválidos');
            }
        }

        $valid = ['PIX','CARTAO_CREDITO'];
        if (!in_array($this->metodo_pagamento, $valid, true)) {
            throw new \InvalidArgumentException('Método de pagamento inválido');
        }
        if ($this->metodo_pagamento === 'CARTAO_CREDITO') {
            if ($this->parcelas !== null && ($this->parcelas < 1 || $this->parcelas > 12)) {
                throw new \InvalidArgumentException('Parcelas devem ser 1..12');
            }
        }
    }
}