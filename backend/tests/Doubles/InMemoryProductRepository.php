<?php
declare(strict_types=1);

namespace Tests\Doubles;

use App\Domain\Product\{Product, ProductRepository};

final class InMemoryProductRepository implements ProductRepository
{
    /** @param array<string, array{name:string, price:float}> $data */
    public function __construct(private array $data) {}

    public function findById(string $id): ?Product
    {
        if (!isset($this->data[$id])) return null;
        $row = $this->data[$id];
        return new Product($id, $row['name'], $row['price']);
    }
    
    public function all(): array
    {
        return $this->data;
    }
}