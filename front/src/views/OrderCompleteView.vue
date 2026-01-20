<script setup>
  import { useRouter } from 'vue-router'
  import { computed, onMounted, ref } from 'vue'
  import { cartService } from '../services/cartService'
  import Header from "../components/Header.vue";

  const router = useRouter()
  const cartItems = cartService.cart
  const total = ref(0)
  const taxRate = 0.1

  onMounted(() => {
    const subtotal = cartService.cart.value.reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    )
    total.value = Math.floor(subtotal * (1 + taxRate))
    cartService.clear()
  })

</script>
  
<template>
  <Header />
  <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
    <div class="bg-white max-w-md w-full p-8 rounded shadow text-center space-y-6">
      <p class="text-gray-600">
        注文が正常に完了しました。
      </p>

      <div class="border-t pt-4 space-y-2 text-sm">
        <div class="flex justify-between">
          <span>合計（税込）</span>
          <span class="font-bold">
            ¥{{ total.toLocaleString() }}
          </span>
        </div>

        <div class="flex justify-between">
          <span>支払い方法</span>
          <span>コンビニ支払い</span>
        </div>
      </div>

      <button
        class="w-full py-3 bg-black text-white rounded hover:opacity-90"
        @click="router.push('/home')"
      >
        商品一覧に戻る
      </button>
    </div>
  </div>
</template>