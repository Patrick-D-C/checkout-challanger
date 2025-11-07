import { defineStore } from 'pinia'
import axios from 'axios'
import type { CartItem, CheckoutResponse, Product } from '../types'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [] as CartItem[],
    metodo_pagamento: 'PIX' as 'PIX' | 'CARTAO_CREDITO',
    parcelas: 1,
    subtotal: 0,
    total: 0,
    parcela: null as number | null,
    lastResponse: null as CheckoutResponse | null,
    loading: false,
    error: '' as string | ''
  }),
  getters: {
    count: (s) => s.items.reduce((a, i) => a + i.quantidade, 0),
    calcSubtotal: (s) => s.items.reduce((a, i) => a + i.price * i.quantidade, 0)
  },
  actions: {
    add(p: Product) {
      const found = this.items.find(i => i.id === p.id)
      if (found) found.quantidade += 1
      else this.items.push({ id: p.id, name: p.name, price: p.price, quantidade: 1 })
    },
    remove(id: string) {
      this.items = this.items.filter(i => i.id !== id)
    },
    setQty(id: string, q: number) {
      const it = this.items.find(i => i.id === id)
      if (!it) return
      it.quantidade = Math.max(1, Math.floor(q || 1))
    },
    clear() {
      this.items = []
      this.lastResponse = null
      this.subtotal = 0
      this.total = 0
      this.parcela = null
    },
    async checkout() {
      if (this.items.length === 0) return
      this.loading = true
      this.error = ''
      try {
        const body = {
          itens: this.items.map(i => ({ id: i.id, quantidade: i.quantidade })),
          metodo_pagamento: this.metodo_pagamento,
          parcelas: this.metodo_pagamento === 'CARTAO_CREDITO' ? this.parcelas : null
        }
        const { data } = await axios.post<CheckoutResponse>('/api/checkout', body)
        this.lastResponse = data
        this.subtotal = data.subtotal
        this.total = data.valor_total
        this.parcela = data.valor_parcela
      } catch (e: any) {
        this.error = e?.response?.data?.erro || 'Falha ao calcular total'
      } finally {
        this.loading = false
      }
    }
  }
})