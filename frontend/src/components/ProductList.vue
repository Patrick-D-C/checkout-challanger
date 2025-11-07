<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useProductsStore } from '../stores/products'
import { useCartStore } from '../stores/cart'

const products = useProductsStore()
const cart = useCartStore()

onMounted(() => {
  if (!products.list.length && !products.loading) {
    void products.fetch()
  }
})
</script>

<template>
  <section class="product-list">
    <header class="product-list__header">
      <h2>Produtos</h2>
      <small v-if="products.loading">carregando…</small>
      <small v-else-if="products.error" class="error">{{ products.error }}</small>
    </header>

    <div v-if="!products.loading && !products.error && products.list.length === 0" class="empty">
      Nenhum produto disponível no momento.
    </div>

    <div class="grid">
      <article v-for="p in products.list" :key="p.id" class="card">
        <RouterLink class="card__link" :to="`/produtos/${p.id}`">
          <h3>{{ p.name }}</h3>
          <span class="price">R$ {{ p.price.toFixed(2) }}</span>
        </RouterLink>
        <button type="button" class="add-button" @click="cart.add(p)">
          Adicionar ao carrinho
        </button>
      </article>
    </div>
  </section>
</template>

<style scoped>
.product-list {
  display: grid;
  gap: 16px;
}

.product-list__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.product-list__header h2 {
  margin: 0;
  font-size: 20px;
}

.error {
  color: #b00;
}

.empty {
  padding: 16px;
  border-radius: 12px;
  border: 1px dashed #ccc;
  color: #555;
  background: #fff;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
}

.card {
  border: 1px solid #eee;
  border-radius: 16px;
  padding: 16px;
  background: #fff;
  display: grid;
  gap: 12px;
}

.card__link {
  display: grid;
  gap: 6px;
  text-decoration: none;
  color: inherit;
}

.card__link h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
}

.price {
  color: #333;
  font-weight: 600;
}

.add-button {
  padding: 9px 12px;
  border-radius: 10px;
  border: none;
  background: #111;
  color: #fff;
  cursor: pointer;
  font-weight: 600;
}

.add-button:hover {
  background: #333;
}
</style>
