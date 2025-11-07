# Checkout Challenge — PHP + Vue.js

Este projeto implementa um **módulo de carrinho de compras completo**, com **API em PHP** e **frontend em Vue.js**, seguindo princípios de **arquitetura limpa**, **SOLID** e **boas práticas de engenharia de software**.

---

## Objetivo

Simular a jornada de compra de um usuário, permitindo:

- Adicionar produtos ao carrinho  
- Escolher forma de pagamento  
- Visualizar valores com **descontos** (Pix / Cartão à vista) ou **juros compostos** (parcelado)  
- Exibir resumo detalhado dos itens e das parcelas  

---

## Filosofia

O projeto foi desenhado como um **teste técnico profissional**, mas pensando em **sustentabilidade e legibilidade** de código a longo prazo.

O foco é demonstrar:
- Clareza arquitetural  
- Separação de responsabilidades  
- Código testável e de fácil evolução  
- Uso racional de padrões e princípios (sem overengineering)


---

## Arquitetura

### **Backend**
- **Linguagem:** PHP 8.2  
- **Padrões:** SOLID, Clean Architecture, DDD (camadas de domínio e infraestrutura)
- **Testes:** PHPUnit  
- **Infraestrutura:** Docker + Nginx  

O domínio está separado em módulos:
- `Money`: encapsula operações monetárias seguras (com `BCMath`)
- `Cart`: representa o carrinho e seus itens
- `Payment`: contém estratégias para cada método de pagamento (Strategy Pattern)
- `Service`: orquestra o processo de checkout
- `Validation`: garante entrada válida e consistente
- `Infrastructure`: abstrai o acesso ao catálogo de produtos (Repository Pattern)

> O backend não usa banco de dados. Os produtos vêm de um arquivo `products.json`.

---

### **Frontend**
- **Framework:** Vue 3 (Vite)
- **Gerenciamento de estado:** Pinia
- **Comunicação:** Axios
- **Build:** Docker (Node + Nginx)

A interface permite que o usuário:
- Liste produtos (obtidos via `/api/products`)
- Monte o carrinho
- Escolha a forma e condição de pagamento
- Visualize o valor total e resumo do pedido

> O Vite faz proxy para o backend (`/api` → `http://backend-nginx:8080`) em modo de desenvolvimento.

---

## Estrutura

```
/backend
  public/index.php                  # roteamento mínimo
  data/products.json                # catálogo (fonte única de preços)
  src/Domain/
    Money/Money.php                 # VO com soma/multiplicação/divisão/subtração (BCMath)
    Cart/{Cart,LineItem}.php        # soma de itens e subtotais
    Payment/                        # Strategy: Pix, 1x, parcelado + Charge DTO
    Product/{Product,ProductRepository}.php
    Service/{CheckoutService,CheckoutResult}.php
    Validation/CheckoutRequest.php  # contrato de entrada (id/quantidade, método, parcelas)
  src/Infrastructure/Product/FileProductRepository.php
  tests/                            # PHPUnit (inclui InMemoryProductRepository)
  composer.json / phpunit.xml

/frontend
  src/
    stores/{products,cart}.ts
    components/*.vue
    views/{HomeView,ProductView,CheckoutView}.vue
    router/index.ts
    types.ts
  vite.config.ts (proxy /api)
  package.json

Dockerfile.backend / Dockerfile.frontend / docker-compose.yml
docker/nginx.backend.conf / docker/nginx.frontend.conf
```

---

## Decisões de design

### **1. SOLID e extensibilidade**
- Cada regra de pagamento é uma **Strategy**, isolada por classe (`PixPayment`, `CreditCardOneShot`, `CreditCardInstallments`).
- O `PaymentContext` injeta a dependência adequada sem ifs espalhados.
- O `CheckoutService` depende de **abstrações** (`ProductRepository`), não de implementações.

> Isso permite adicionar novos métodos (ex.: boleto, débito, carteira digital) sem alterar código existente — apenas adicionando novas classes.

---

### **2. Precisão nos cálculos**
- O domínio usa `BCMath` para manter precisão em cálculos monetários.
- O `Value Object` `Money` garante arredondamento controlado.
- Descontos e juros seguem as fórmulas especificadas com fidelidade.

---

### **3. Segurança e integridade**
- O cliente envia apenas `id` e `quantidade` dos produtos.
- Os preços são resolvidos no backend via `FileProductRepository` — **evitando manipulação de valores**.
- Validação rígida impede valores ou métodos inconsistentes.

---

### **4. Testes automatizados**
- Testes unitários cobrem regras de negócio centrais:
  - Cálculo de desconto e juros
  - Limites de parcelas
  - Integridade de produtos
- Repositório fake (`InMemoryProductRepository`) garante testes rápidos e determinísticos.

---

### **5. Containerização**
Ambos os serviços (frontend e backend) têm **Dockerfiles separados** e um `docker-compose.yml` que orquestra:
- `backend-nginx` (porta 8080)
- `frontend` (porta 8081)

### **Subir o projeto**
```bash
docker compose up --build
# Frontend → http://localhost:8081
# API → http://localhost:8080/api
```

---

## Endpoints

### **GET /api/products**
Retorna a lista de produtos disponíveis (lidos de `products.json`).

### **POST /api/checkout**
Calcula o total da compra e retorna o resumo:
```json
{
  "subtotal": 350.00,
  "valor_total": 315.00,
  "metodo_pagamento": "PIX",
  "parcelas": 1,
  "valor_parcela": 315.00,
  "itens": [
    { "id": "fone-bluetooth", "nome": "Fone Bluetooth", "quantidade": 2, "unitario": 100.00, "subtotal": 200.00 },
    { "id": "mouse-gamer", "nome": "Mouse Gamer", "quantidade": 1, "unitario": 150.00, "subtotal": 150.00 }
  ]
}
```

---

## Pontos que poderiam ser melhorados

1. **Persistência real**  
   Integrar `ProductRepository` com banco (ex.: MySQL ou Redis), mantendo a interface estável.

2. **Cupons, impostos e taxas**  
   Implementar policies (Decorator Pattern) para aplicar impostos e cupons sem modificar a lógica principal.

3. **Internacionalização e moedas**  
   Suporte a múltiplas moedas (`USD`, `EUR`, etc.) via `MoneyFormatter` e `CurrencyConverter`.

4. **Testes de integração (E2E)**  
   Cobrir o fluxo completo com HTTP tests e snapshots JSON.

---

## Como rodar

### Ambiente Docker (produção local: frontend + backend)
```bash
docker compose up --build
# Frontend → http://localhost:8081
# API      → http://localhost:8080/api
```

### Desenvolvimento
- **Backend**: `composer install` em `backend/` e rode via Docker (`backend-nginx`) ou PHP embutido (ajuste conforme sua preferência).
- **Frontend**:
  ```bash
  cd frontend
  npm install
  npm run dev  # http://localhost:5173
  ```
  O Vite já proxy-a `/api` → `http://localhost:8080`.

### Testes (backend)
```bash
cd backend
composer install
vendor/bin/phpunit
```