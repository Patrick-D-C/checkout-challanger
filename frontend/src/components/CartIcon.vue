<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const cart = useCartStore()

const hasItems = computed(() => cart.count > 0)

const goToCheckout = () => {
  router.push({ name: 'checkout' })
}
</script>

<template>
  <button v-if="hasItems" class="cart-icon" type="button" @click="goToCheckout">
    <svg
      class="icon"
      width="20"
      height="20"
      viewBox="0 0 24 24"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <path
        d="M3 3h2l.4 2M7 13h11l2-6H5.4"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
      <circle cx="9" cy="19" r="1.5" fill="currentColor" />
      <circle cx="18" cy="19" r="1.5" fill="currentColor" />
    </svg>
    <span class="badge">{{ cart.count }}</span>
    <span class="sr-only">Ir para o carrinho</span>
  </button>
</template>

<style scoped>
.cart-icon {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 14px;
  border-radius: 999px;
  border: 1px solid #222;
  background: #111;
  color: #fff;
  cursor: pointer;
  font-weight: 600;
}

.cart-icon:hover {
  background: #222;
}

.badge {
  min-width: 26px;
  height: 26px;
  border-radius: 999px;
  background: #fff;
  color: #111;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: 700;
  padding: 0 8px;
}

.icon {
  flex-shrink: 0;
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}
</style>
