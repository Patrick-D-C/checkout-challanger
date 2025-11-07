<?php
declare(strict_types=1);

namespace App\Domain\Product;

interface ProductRepository
{
    public function findById(string $id): ?Product;
    /** @return array<Product> */
    public function all(): array;
}
