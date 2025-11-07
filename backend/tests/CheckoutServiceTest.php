<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Domain\Service\CheckoutService;
use App\Domain\Validation\CheckoutRequest;
use Tests\Doubles\InMemoryProductRepository;

final class CheckoutServiceTest extends TestCase
{
    private function repo(): InMemoryProductRepository
    {
        return new InMemoryProductRepository([
            'fone' => ['name' => 'Fone', 'price' => 100.00],
            'mouse' => ['name' => 'Mouse', 'price' => 150.00],
            'item' => ['name' => 'Item', 'price' => 120.00],
        ]);
    }

    public function testPixDesconto10(): void
    {
        $svc = new CheckoutService($this->repo());
        $dto = new CheckoutRequest(
            itens: [
                ['id' => 'fone', 'quantidade' => 2],
                ['id' => 'mouse', 'quantidade' => 1],
            ],
            metodo_pagamento: 'PIX',
            parcelas: null
        );
        $res = $svc->handle($dto);
        $this->assertSame('315.00', $res->charge->total->value()); // (200+150)*0.9
    }

    public function testCartaoUmxDesconto10(): void
    {
        $svc = new CheckoutService($this->repo());
        $dto = new CheckoutRequest(
            itens: [['id' => 'item', 'quantidade' => 1]],
            metodo_pagamento: 'CARTAO_CREDITO',
            parcelas: 1
        );
        $res = $svc->handle($dto);
        $this->assertSame('108.00', $res->charge->total->value()); // 120*0.9
    }

    public function testCartaoParceladoJurosCompostos(): void
    {
        $svc = new CheckoutService($this->repo());
        $dto = new CheckoutRequest(
            itens: [['id' => 'item', 'quantidade' => 1]],
            metodo_pagamento: 'CARTAO_CREDITO',
            parcelas: 3
        );
        $res = $svc->handle($dto);
        $this->assertSame('123.64', $res->charge->total->value()); // 120*(1.01)^3
        $this->assertSame('41.21', $res->charge->installmentValue->value()); // 123.64/3
        $this->assertSame(3, $res->charge->installments);
    }

    public function testProdutoInexistenteDeveFalhar(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $svc = new CheckoutService($this->repo());
        $dto = new CheckoutRequest(
            itens: [['id' => 'x', 'quantidade' => 1]],
            metodo_pagamento: 'PIX',
            parcelas: null
        );
        $svc->handle($dto);
    }
}