<script setup lang="ts">
import { computed, watch } from 'vue'
import { useCartStore } from '../stores/cart'

const cart = useCartStore()
const isCard = computed(() => cart.metodo_pagamento === 'CARTAO_CREDITO')

watch(
  () => [cart.metodo_pagamento, cart.parcelas, cart.items],
  () => { /* pode-se deboucear; deixamos manual via botão Calcular */ },
  { deep: true }
)
</script>

<template>
  <section>
    <h2 style="margin:0 0 8px 0;font-size:18px">Pagamento</h2>

    <div style="display:grid;gap:8px;align-items:center;grid-template-columns:160px 1fr;max-width:460px">
      <label>Forma</label>
      <select v-model="cart.metodo_pagamento" style="padding:8px;border-radius:8px;border:1px solid #ddd">
        <option value="PIX">Pix (10% desc.)</option>
        <option value="CARTAO_CREDITO">Cartão de Crédito</option>
      </select>

      <label v-if="isCard">Parcelas</label>
      <div v-if="isCard">
        <input type="range" min="1" max="12" v-model.number="cart.parcelas" />
        <div style="font-size:12px;opacity:.8">{{ cart.parcelas }}x</div>
      </div>

      <div></div>
      <button :disabled="!cart.items.length || cart.loading" @click="cart.checkout()" style="padding:10px 12px;border-radius:8px;border:1px solid #333;background:#111;color:#fff;cursor:pointer">
        {{ cart.loading ? 'Calculando…' : 'Calcular total' }}
      </button>
    </div>

    <p v-if="cart.error" style="color:#b00;margin-top:8px">{{ cart.error }}</p>
  </section>
</template>