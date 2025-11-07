<script setup lang="ts">
import { computed, onMounted, watch } from 'vue'
import { RouterLink } from 'vue-router'
import { useProductsStore } from '../stores/products'
import { useCartStore } from '../stores/cart'

const props = defineProps<{ id: string }>()

const products = useProductsStore()
const cart = useCartStore()

const product = computed(() => products.byId(props.id))

const ensureProductsLoaded = () => {
  if (!products.list.length && !products.loading) {
    void products.fetch()
    return
  }
  if (!product.value && !products.loading) {
    void products.fetch(true)
  }
}

onMounted(() => {
  ensureProductsLoaded()
})

watch(
  () => props.id,
  () => {
    ensureProductsLoaded()
  }
)

const addToCart = () => {
  if (product.value) {
    cart.add(product.value)
  }
}
</script>

<template>
  <section class="product-page">
    <RouterLink class="back-link" to="/">← Voltar aos produtos</RouterLink>

    <div v-if="products.loading && !product" class="feedback">Carregando…</div>
    <div v-else-if="products.error" class="feedback error">{{ products.error }}</div>
    <div v-else-if="!product" class="feedback">Produto não encontrado.</div>
    <div v-else class="product-card">
      <header>
        <h1>{{ product.name }}</h1>
        <p class="price">R$ {{ product.price.toFixed(2) }}</p>
      </header>
      <p>Adicione este produto ao carrinho para finalizar sua compra.</p>
      <button class="add-button" type="button" @click="addToCart">
        Adicionar ao carrinho
      </button>
    </div>
  </section>
</template>

<style scoped>
.product-page {
  display: grid;
  gap: 24px;
  max-width: 640px;
}

.back-link {
  color: #1a73e8;
  text-decoration: none;
  font-size: 14px;
}

.back-link:hover {
  text-decoration: underline;
}

.feedback {
  padding: 24px;
  border-radius: 12px;
  background: #f6f6f6;
}

.feedback.error {
  color: #b00;
}

.product-card {
  padding: 32px;
  border-radius: 16px;
  border: 1px solid #eee;
  background: #fff;
  display: grid;
  gap: 16px;
}

.product-card h1 {
  margin: 0;
  font-size: 32px;
  font-weight: 700;
}

.price {
  font-size: 20px;
  font-weight: 600;
  color: #222;
  margin: 8px 0 0;
}

.add-button {
  padding: 12px 16px;
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
