<script setup>
  import { ref, computed } from 'vue'
  import { useRouter } from 'vue-router'
  import { cartService } from '../services/cartService'
  import { api } from "../libs/api"
  import Header from "../components/Header.vue"
  
  const router = useRouter()
  const cartItems = cartService.cart

  const lastName = ref('山田')
  const firstName = ref('ジョンソン')
  const lastNameKana = ref('ヤマダ')
  const firstNameKana = ref('ジョンソン')
  const postal = ref('150-0000')
  const address = ref('東京都山田区ジョンソン東1-2-3')
  const phone = ref('090-1234-5678')
  
  const isLoading = ref(false)
  const zipError = ref('')
  const errors = ref({})
  
  const taxRate = 0.1
  
  // const subtotal = computed(() =>
  //   cartItems.value.reduce(
  //     (sum, item) => sum + item.price * item.quantity,
  //     0
  //   )
  // )

  const subtotal = computed(() =>
  cartItems.value.reduce((sum, item) => {
    const price = Number(item.price) || 0
    const quantity = Number(item.quantity) || 0
    return sum + price * quantity
  }, 0)
)
  
  const totalWithTax = computed(() =>
    Math.floor(subtotal.value * (1 + taxRate))
  )
  

  // 注文確定
  const submitOrder = async () => {
    errors.value = {}
  
    try {
      await api.post('/order/store', {
        items: cartItems.value,
        last_name: lastName.value,
        first_name: firstName.value,
        last_name_kana: lastNameKana.value,
        first_name_kana: firstNameKana.value,
        postal: postal.value,
        address: address.value,
        phone: phone.value,
      })
  
      router.push('/complete')
  
    } catch (err) {
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors
      } else {
        alert(
          err.response?.data?.message ??
          '注文処理に失敗しました'
        )
      }
    }
  }
  
  const searchAddress = async () => {
    zipError.value = ''
  
    const zip = postal.value.replace('-', '')
    if (!/^\d{7}$/.test(zip)) {
      zipError.value = '郵便番号は7桁で入力してください'
      return
    }
  
    isLoading.value = true
  
    try {
      const res = await fetch(
        `https://zipcloud.ibsnet.co.jp/api/search?zipcode=${zip}`
      )
      const data = await res.json()
  
      if (data.results) {
        const r = data.results[0]
        address.value = `${r.address1}${r.address2}${r.address3}`
      } else {
        zipError.value = '住所が見つかりませんでした'
      }
    } catch (e) {
      zipError.value = '住所検索に失敗しました'
    } finally {
      isLoading.value = false
    }
  }

  console.log(cartItems.value)

  </script>
  
  <template>
    <Header />
  
    <div class="min-h-screen bg-gray-100 p-8 max-w-3xl mx-auto space-y-6">
      <h1 class="text-2xl font-bold">レジ</h1>
  
      <div
        v-if="Object.keys(errors).length"
        class="bg-red-50 border border-red-300 text-red-700 p-4 rounded"
      >
        入力内容に誤りがあります。各項目をご確認ください。
      </div>
  
      <div class="bg-white p-6 rounded shadow space-y-4">
        <h2 class="font-bold">配送先情報（<span class="text-red-500">ポートフォリオですので、入力情報を保存しません</span>）</h2>
        <div>
          <label class="block text-sm font-medium mb-1">氏名</label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <div>
              <input v-model="lastName" class="border rounded p-2 w-full" placeholder="姓" />
              <p v-if="errors.last_name" class="text-sm text-red-500">
                {{ errors.last_name[0] }}
              </p>
            </div>
            <div>
              <input v-model="firstName" class="border rounded p-2 w-full" placeholder="名" />
              <p v-if="errors.first_name" class="text-sm text-red-500">
                {{ errors.first_name[0] }}
              </p>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">フリガナ</label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <div>
              <input v-model="lastNameKana" class="border rounded p-2 w-full" placeholder="セイ" />
              <p v-if="errors.last_name_kana" class="text-sm text-red-500">
                {{ errors.last_name_kana[0] }}
              </p>
            </div>
            <div>
              <input v-model="firstNameKana" class="border rounded p-2 w-full" placeholder="メイ" />
              <p v-if="errors.first_name_kana" class="text-sm text-red-500">
                {{ errors.first_name_kana[0] }}
              </p>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">郵便番号</label>
          <div class="flex gap-2">
            <input
              v-model="postal"
              class="border rounded p-2 w-40 sm:w-48" 
              placeholder="123-4567"
            />
            <button
              type="button"
              class="px-4 bg-gray-800 text-white rounded text-sm"
              @click="searchAddress"
              :disabled="isLoading"
            >
              {{ isLoading ? '検索中...' : '住所検索' }}
            </button>
          </div>
          <p v-if="zipError" class="text-sm text-red-500">{{ zipError }}</p>
          <p v-if="errors.postal" class="text-sm text-red-500">
            {{ errors.postal[0] }}
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">住所</label>
          <input v-model="address" class="border rounded p-2 w-full" />
          <p v-if="errors.address" class="text-sm text-red-500">
            {{ errors.address[0] }}
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">電話番号</label>
          <input v-model="phone" class="border rounded p-2 w-full sm:w-64" />
          <p v-if="errors.phone" class="text-sm text-red-500">
            {{ errors.phone[0] }}
          </p>
        </div>
      </div>

      <div class="bg-white p-6 rounded shadow">
        <h2 class="font-bold mb-2">支払い方法</h2>
        <label class="flex items-center gap-2 opacity-70">
          <input type="radio" checked disabled />
          <span>コンビニ支払い</span>
        </label>
      </div>

      <div class="bg-white p-6 rounded shadow">
        <div class="flex justify-between text-sm mb-2">
          <span>小計</span>
          <span>¥{{ subtotal.toLocaleString() }}</span>
        </div>
        <div class="flex justify-between text-lg font-bold">
          <span>合計（税込）</span>
          <span>¥{{ totalWithTax.toLocaleString() }}</span>
        </div>
      </div>

      <button
        class="w-full py-4 bg-black text-white rounded hover:opacity-90"
        @click="submitOrder"
      >
        注文を確定する
      </button>
    </div>
  </template>
  