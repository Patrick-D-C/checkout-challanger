<?php
declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Money\Money;

final class CreditCardInstallments implements PaymentMethod
{
    public function __construct(
        private int $installments, // Parcelas 2 a 12
        private string $monthlyRate = '0.01' // 1% a.m
    ) {
        if ($installments < 2 || $installments > 12) {
            throw new \InvalidArgumentException('Parcelas devem ser 2..12');
        }
    }

    /**
     * Função responsavel por aplicar o calculo base
     * M = P * (1 + i)^n
     */
    public function finalize(Money $principal): Charge
    {
        $base = bcadd('1', $this->monthlyRate, 6);
        $factor = $this->pow($base, $this->installments);
        $total = $principal->mul($factor, 4);
        $parcel = $total->div((string)$this->installments, 4);

        return new Charge(
            total: $total,
            method: 'CARTAO_CREDITO',
            installments: $this->installments,
            installmentValue: $parcel
        );
    }

    private function pow(string $base, int $exp): string
    {
        $result = '1';
        for ($k = 0; $k < $exp; $k++) {
            $result = bcmul($result, $base, 6);
        }
        return $result;
    }
}