<script setup lang="ts">
import { computed } from 'vue'
import { useCartStore } from '../stores/cart'

const cart = useCartStore()

const discountValue = computed(() => {
  const value = cart.lastResponse?.desconto ?? null
  return value && value > 0 ? value : null
})

const isCreditCard = computed(() => cart.lastResponse?.metodo_pagamento === 'CARTAO_CREDITO')
const subtotalValue = computed(() => cart.lastResponse?.subtotal ?? cart.calcSubtotal)
</script>

<template>
  <section>
    <h2 class="title">Resumo</h2>

    <div class="summary-card">
      <div class="row">
        <span>Subtotal</span>
        <strong>R$ {{ subtotalValue.toFixed(2) }}</strong>
      </div>

      <template v-if="cart.lastResponse">
        <div class="divider" />
        <div class="row">
          <span>Método</span>
          <strong>{{ cart.lastResponse.metodo_pagamento }}</strong>
        </div>

        <div v-if="discountValue" class="row highlight">
          <span>Desconto</span>
          <strong>- R$ {{ discountValue.toFixed(2) }}</strong>
        </div>

        <div v-if="isCreditCard" class="row">
          <span>Parcelas</span>
          <strong>{{ cart.lastResponse.parcelas }}</strong>
        </div>

        <div v-if="isCreditCard && cart.lastResponse.valor_parcela" class="row">
          <span>Valor da parcela</span>
          <strong>R$ {{ cart.lastResponse.valor_parcela.toFixed(2) }}</strong>
        </div>

        <div class="row total">
          <span>Total</span>
          <strong>R$ {{ cart.lastResponse.valor_total.toFixed(2) }}</strong>
        </div>
      </template>

      <p v-else class="hint">Clique em “Calcular total” para aplicar descontos/juros.</p>
    </div>
  </section>
</template>

<style scoped>
.title {
  margin: 0 0 8px 0;
  font-size: 18px;
}

.summary-card {
  border: 1px solid #eee;
  border-radius: 12px;
  padding: 12px;
  max-width: 460px;
  display: grid;
  gap: 8px;
}

.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
}

.row strong {
  font-weight: 600;
}

.divider {
  border-top: 1px dashed #eee;
  margin: 4px 0;
}

.highlight {
  color: #0a8754;
}

.total {
  font-size: 18px;
  margin-top: 4px;
}

.hint {
  opacity: 0.7;
  margin: 8px 0 0;
}
</style>
