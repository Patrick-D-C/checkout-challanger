<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import CartIcon from './components/CartIcon.vue'
</script>

<template>
  <div class="app">
    <header class="app-header">
      <RouterLink class="logo" to="/">Checkout Challanger</RouterLink>
      <nav class="app-nav">
        <RouterLink to="/">Início</RouterLink>
        <RouterLink to="/checkout">Checkout</RouterLink>
      </nav>
      <div class="cart-slot">
        <CartIcon />
      </div>
    </header>

    <main class="app-main">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
.app {
  min-height: 100vh;
  background: #fafafa;
}

.app-header {
  max-width: 1080px;
  margin: 0 auto;
  padding: 24px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.logo {
  color: #111;
  text-decoration: none;
  font-size: 22px;
  font-weight: 700;
}

.app-nav {
  display: flex;
  align-items: center;
  gap: 16px;
}

.app-nav a {
  color: #444;
  text-decoration: none;
  font-weight: 600;
  font-size: 15px;
}

.app-nav a.router-link-active {
  color: #111;
}

.cart-slot {
  min-width: 150px;
  display: flex;
  justify-content: flex-end;
}

.app-main {
  max-width: 1080px;
  margin: 0 auto;
  padding: 0 16px 40px;
}
</style>
