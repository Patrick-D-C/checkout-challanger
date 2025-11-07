<?php
declare(strict_types=1);

namespace App\Domain\Money;

use function bcadd;
use function bcdiv;
use function bcmul;
use function bcsub;

final class Money
{
    private string $amount; // decimal como string, 2 casas no output
    private string $currency;

    public function __construct(string $amount, string $currency = 'BRL')
    {
        $this->amount = number_format((float)$amount, 2, '.', '');
        $this->currency = $currency;
    }

    public static function zero(string $currency = 'BRL'): self
    {
        return new self('0.00', $currency);
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);
        $sum = bcadd($this->amount, $other->amount, 2);

        return new self($sum, $this->currency);
    }

    public function mul(string $factor, int $scale = 2): self
    {
        $prod = bcmul($this->amount, $factor, $scale);
        return new self(number_format((float)$prod, 2, '.', ''), $this->currency);
    }

    public function sub(self $other): self
    {
        $this->assertSameCurrency($other);
        $diff = bcsub($this->amount, $other->amount, 2);

        return new self($diff, $this->currency);
    }

    public function div(string $divisor, int $scale = 2): self
    {
        if ($divisor === '0' || (float)$divisor === 0.0) {
            throw new \InvalidArgumentException('Divisão por zero');
        }

        $q = bcdiv($this->amount, $divisor, $scale);

        return new self(number_format((float)$q, 2, '.', ''), $this->currency);
    }

    public function value(): string
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Different currencies');
        }
    }
}
