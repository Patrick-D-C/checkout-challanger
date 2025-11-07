export type Product = {
  id: string
  name: string
  price: number
}

export type CartItem = {
  id: string
  name: string
  price: number
  quantidade: number
}

export type CheckoutResponse = {
  subtotal: number
  valor_total: number
  metodo_pagamento: 'PIX' | 'CARTAO_CREDITO'
  parcelas: number
  valor_parcela: number | null
  desconto: number | null
  itens: { id: string; nome: string; quantidade: number; unitario: string; subtotal: string }[]
}
