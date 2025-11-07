<script setup lang="ts">
import CartList from '../components/CartList.vue'
import PaymentForm from '../components/PaymentForm.vue'
import CheckoutSummary from '../components/CheckoutSummary.vue'
</script>

<template>
  <section class="checkout">
    <div class="checkout-left">
      <CartList />
    </div>
    <aside class="checkout-right">
      <PaymentForm />
      <CheckoutSummary />
    </aside>
  </section>
</template>

<style scoped>
.checkout {
  display: grid;
  gap: 24px;
  grid-template-columns: 2fr 1fr;
}

@media (max-width: 960px) {
  .checkout {
    grid-template-columns: 1fr;
  }
}

.checkout-left,
.checkout-right {
  display: grid;
  gap: 24px;
}
</style>
