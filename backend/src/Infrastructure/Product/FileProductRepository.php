<?php
declare(strict_types=1);

namespace App\Infrastructure\Product;

use App\Domain\Product\{Product, ProductRepository};

final class FileProductRepository implements ProductRepository
{
    /** @var array<string, Product>|null */
    private ?array $cache = null;

    public function __construct(private string $filePath) {}

    public function findById(string $id): ?Product
    {
        $map = $this->load();
        return $map[$id] ?? null;
    }

    /** @return array<Product> */
    public function all(): array
    {
        return $this->load();
    }
    
    /** @return array<Product> */
    private function load(): array
    {
        if ($this->cache !== null) return $this->cache;
        if (!file_exists($this->filePath)) return $this->cache = [];

        $json = file_get_contents($this->filePath);
        $data = $json ? json_decode($json, true) : [];
        $map = [];
        if (is_array($data)) {
            foreach ($data as $row) {
                if (!isset($row['id'],$row['nome'],$row['valor'])) continue;
                $p = new Product(
                    id: (string)$row['id'],
                    name: (string)$row['nome'],
                    price: (float)$row['valor']
                );
                $map[$p->id] = $p;
            }
        }
        return $this->cache = $map;
    }
}