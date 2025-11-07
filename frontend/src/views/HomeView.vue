<script setup lang="ts">
import ProductList from '../components/ProductList.vue'
</script>

<template>
  <section class="page">
    <header class="page-header">
      <h1>Produtos</h1>
      <p>Escolha um item para ver detalhes ou adicione direto ao carrinho.</p>
    </header>
    <ProductList />
  </section>
</template>

<style scoped>
.page {
  display: grid;
  gap: 24px;
}

.page-header h1 {
  margin: 0;
  font-size: 28px;
  font-weight: 700;
}

.page-header p {
  margin: 4px 0 0;
  color: #555;
}
</style>
