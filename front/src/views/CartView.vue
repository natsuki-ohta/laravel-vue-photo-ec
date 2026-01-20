<script setup>
  import { ref, computed, onMounted, watch } from 'vue'
  import { useRouter } from 'vue-router'
  import Header from '../components/Header.vue'
  import { api } from '../libs/api'
  
  const router = useRouter()
  const cartItems = ref([])
  const CART_KEY = 'cart'
  const loadCart = () => {
    const raw = localStorage.getItem(CART_KEY)
    return raw ? JSON.parse(raw) : []
  }
  const saveCart = () => {
    const data = cartItems.value.map(item => ({
      id: item.id,
      quantity: item.quantity,
      name: item.name,
      price: item.price,
      stock: item.stock,
    }))
    localStorage.setItem(CART_KEY, JSON.stringify(data))
  }

  watch(cartItems, saveCart, { deep: true })
  const fetchCartItems = async () => {
    const cart = loadCart()
  
    if (cart.length === 0) {
      cartItems.value = []
      return
    }
  
    const ids = cart.map(item => item.id).join(',')
  
    const res = await api.get('/products', {
      params: { ids },
    })
  
    cartItems.value = res.data.data.map(product => {
      const cartItem = cart.find(
        c => Number(c.id) === Number(product.id)
      )
  
      return {
        ...product,
        quantity: cartItem.quantity,
      }
    })
  }

  const updateQuantity = (item, qty) => {
    const safeQty = Math.min(
      Math.max(qty, 1),
      item.stock
    )
  
    item.quantity = safeQty
  }
  
  const removeItem = (id) => {
    if (!window.confirm('カートから商品を削除しますか？')) return
    cartItems.value = cartItems.value.filter(item => item.id !== id)
  }

  const totalQuantity = computed(() =>
    cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
  )
  
  const subtotal = computed(() =>
    cartItems.value.reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    )
  )
  
  const TAX_RATE = 0.1
  const totalWithTax = computed(() =>
    Math.floor(subtotal.value * (1 + TAX_RATE))
  )
  
  onMounted(fetchCartItems)
  </script>
  
  <template>
    <Header />
  
    <h1 class="text-2xl font-bold mb-3 mt-3 pl-8">カート一覧</h1>
  
    <div v-if="cartItems.length === 0" class="text-gray-600">
      <p class="ml-8">カートは空です</p>
    </div>
  
    <div
      v-else
      class="min-h-screen bg-gray-100 p-8 grid grid-cols-1 lg:grid-cols-3 gap-6"
    >
      <div class="lg:col-span-2 space-y-4">
        <div
          v-for="item in cartItems"
          :key="item.id"
          class="bg-white p-4 rounded shadow flex flex-col sm:flex-row gap-4 items-start sm:items-center"
        >
          <img
            :src="item.image_path"
            class="w-full sm:w-32 h-32 object-cover rounded"
          />
  
          <div class="flex-1">
            <h2 class="font-bold">{{ item.name }}</h2>
            <p class="text-sm text-gray-600">¥{{ item.price }}</p>
            <p class="text-xs text-gray-500">在庫：{{ item.stock }}</p>
          </div>
  
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
            <input
              type="number"
              min="1"
              :max="item.stock"
              :value="item.quantity"
              @change="updateQuantity(item, Number($event.target.value))"
              class="w-20 sm:w-16 border rounded p-1 text-center"
            />
  
            <button
              @click="removeItem(item.id)"
              class="text-red-500 text-sm hover:underline"
            >
              削除
            </button>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded shadow h-fit">
        <div class="flex justify-between text-sm text-gray-600 mb-2">
          <span>小計（{{ totalQuantity }} 個の商品）</span>
          <span>¥{{ subtotal.toLocaleString() }}</span>
        </div>

        <div class="flex justify-between text-lg font-bold mb-4">
          <span>合計（税込）</span>
          <span>¥{{ totalWithTax.toLocaleString() }}</span>
        </div>

        <button
          class="w-full py-3 bg-black text-white rounded hover:opacity-90"
          @click="router.push('/checkout')"
        >
          レジに進む
        </button>

        <button
          class="mt-4 w-full py-2 border rounded"
          @click="router.push('/home')"
        >
          商品一覧に戻る
        </button>
      </div>
    </div>
  </template>
  