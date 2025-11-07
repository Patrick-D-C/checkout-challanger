<?php
declare(strict_types=1);

namespace App\Domain\Product;

final class Product
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float $price
    ) {}
}