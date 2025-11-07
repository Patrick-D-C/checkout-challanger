<script setup lang="ts">
import { useCartStore } from '../stores/cart'
const cart = useCartStore()
</script>

<template>
  <section>
    <header style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
      <h2 style="margin:0;font-size:18px">Carrinho ({{ cart.count }})</h2>
      <button v-if="cart.items.length" @click="cart.clear()" style="background:none;border:none;color:#b00;cursor:pointer">limpar</button>
    </header>

    <div v-if="!cart.items.length" style="opacity:.7">Seu carrinho está vazio.</div>

    <table v-else style="width:100%;border-collapse:collapse">
      <thead>
        <tr style="text-align:left;border-bottom:1px solid #eee">
          <th>Produto</th>
          <th style="width:90px">Qtd</th>
          <th style="width:120px">Unit.</th>
          <th style="width:120px">Subtotal</th>
          <th style="width:60px"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="i in cart.items" :key="i.id" style="border-bottom:1px solid #f3f3f3">
          <td>{{ i.name }}</td>
          <td>
            <input type="number" min="1" :value="i.quantidade" @input="cart.setQty(i.id, Number(($event.target as HTMLInputElement).value))" style="width:70px" />
          </td>
          <td>R$ {{ i.price.toFixed(2) }}</td>
          <td>R$ {{ (i.price * i.quantidade).toFixed(2) }}</td>
          <td>
            <button @click="cart.remove(i.id)" style="border:none;background:none;color:#b00;cursor:pointer">remover</button>
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</template>