import { defineStore } from 'pinia'
import axios from 'axios'
import type { Product } from '../types'

export const useProductsStore = defineStore('products', {
  state: () => ({
    list: [] as Product[],
    loading: false,
    error: '' as string | ''
  }),
  getters: {
    byId: (state) => (id: string) => state.list.find((product) => product.id === id) ?? null
  },
  actions: {
    async fetch(force = false) {
      if (!force && this.list.length) {
        return
      }
      this.loading = true
      this.error = ''
      try {
        const { data } = await axios.get<Product[]>('/api/products')
        this.list = data
      } catch (e: any) {
        this.error = 'Falha ao carregar produtos'
      } finally {
        this.loading = false
      }
    }
  }
})
