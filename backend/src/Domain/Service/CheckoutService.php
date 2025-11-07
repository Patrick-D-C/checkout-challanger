<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Cart\{Cart, LineItem};
use App\Domain\Money\Money;
use App\Domain\Payment\{PaymentContext, PixPayment, CreditCardOneShot, CreditCardInstallments};
use App\Domain\Product\ProductRepository;
use App\Domain\Validation\CheckoutRequest;

final class CheckoutService
{
    public function __construct(private ProductRepository $products) {}

    public function handle(CheckoutRequest $req): CheckoutResult
    {
        $req->validate();

        $cart = new Cart('BRL');
        $viewItems = [];

        foreach ($req->itens as $i) {
            $product = $this->products->findById($i['id']);
            if (!$product) {
                throw new \InvalidArgumentException('Produto não encontrado: ' . $i['id']);
            }
            $line = new LineItem(
                $product->name,
                new Money(number_format($product->price, 2, '.', '')),
                (int) $i['quantidade']
            );
            $cart->addItem($line);

            $unit = $line->subtotal()->div((string)$i['quantidade']);
            $viewItems[] = [
                'id' => $product->id,
                'nome' => $product->name,
                'quantidade' => (int)$i['quantidade'],
                'unitario' => $unit->value(),
                'subtotal' => $line->subtotal()->value()
            ];
        }

        $principal = $cart->total();

        $method = match ($req->metodo_pagamento) {
            'PIX' => new PixPayment(),
            'CARTAO_CREDITO' =>
                ($req->parcelas === null || $req->parcelas === 1)
                ? new CreditCardOneShot()
                : new CreditCardInstallments($req->parcelas),
            default => throw new \InvalidArgumentException('Método de pagamento inválido')
        };

        $ctx = new PaymentContext($method);
        $charge = $ctx->charge($principal);

        $discount = $principal->sub($charge->total);
        $hasDiscount = (float)$discount->value() > 0;

        return new CheckoutResult(
            items: $viewItems,
            subtotal: $principal,
            charge: $charge,
            discount: $hasDiscount ? $discount : null
        );
    }
}
