<?php
declare(strict_types=1);

use App\Domain\Service\CheckoutService;
use App\Domain\Validation\CheckoutRequest;
use App\Infrastructure\Product\FileProductRepository;

require __DIR__ . '/../vendor/autoload.php';
header('Content-Type: application/json');

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($path === '/api/products' && $method === 'GET') {
        $repo = new \App\Infrastructure\Product\FileProductRepository(__DIR__ . '/../data/products.json');
        echo json_encode($repo->all(), JSON_THROW_ON_ERROR);
        exit;
    }

    if ($path === '/api/checkout' && $method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

        $dto = new CheckoutRequest(
            itens: $body['itens'] ?? [],
            metodo_pagamento: $body['metodo_pagamento'] ?? '',
            parcelas: $body['parcelas'] ?? null
        );

        $repo = new FileProductRepository(__DIR__ . '/../data/products.json');
        $service = new CheckoutService($repo);
        $result = $service->handle($dto);

        $response = [
            'subtotal' => (float)$result->subtotal->value(),
            'valor_total' => (float)$result->charge->total->value(),
            'metodo_pagamento' => $result->charge->method,
            'parcelas' => $result->charge->installments,
            'valor_parcela' => $result->charge->installmentValue ? (float)$result->charge->installmentValue->value() : null,
            'desconto' => $result->discount ? (float)$result->discount->value() : null,
            'itens' => $result->items,
        ];

        echo json_encode($response, JSON_THROW_ON_ERROR);
        exit;
    }

    http_response_code(404);
    echo json_encode(['erro' => 'Not Found']);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['erro' => $e->getMessage()]);
}
