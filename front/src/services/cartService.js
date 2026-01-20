import { ref } from 'vue'
import { StorageUtils } from './storageUtils'

const CART_KEY = 'cart'
const cart = ref(StorageUtils.get(CART_KEY, []))

const findItem = (id) => {
  return cart.value.find(item => item.id === id)
}

export const cartService = {
  cart,

  /**
   * 商品をカートに追加
   */
  setItem(item) {
    const existing = findItem(item.id)
    const currentQty = existing ? existing.quantity : 0
    const nextQty = currentQty + item.quantity

    if (nextQty > item.stock) {
      alert(`在庫は残り ${item.stock - currentQty} 個です`)
      return
    }

    if (existing) {
      existing.quantity = nextQty
    } else {
      // cart.value.push({
      //   ...item,
      //   quantity: item.quantity,
      // })

      cart.value.push({
        id: item.id,
        name: item.name,
        price: Number(item.price),
        quantity: Number(item.quantity),
      })

    }

    StorageUtils.set(CART_KEY, cart.value);
  },

  /**
   * 商品削除
   */
  removeItem(id) {
    cart.value = cart.value.filter(item => item.id !== id)
    StorageUtils.set(CART_KEY, cart.value);
  },

  /**
   * 数量変更
   */
  updateQuantity(id, quantity) {
    const item = findItem(id)
    if (!item) return

    item.quantity = quantity
    StorageUtils.set(CART_KEY, cart.value);
  },

  /**
   * カート初期化
   */
  clear() {
    cart.value = []
    StorageUtils.remove(CART_KEY)
  },

  /**
   * 合計個数
   */
  getItemCount() {
    return cart.value.reduce(
      (sum, item) => sum + item.quantity,
      0
    )
  },

  /**
   * 合計金額
   */
  getTotalPrice() {
    return cart.value.reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    )
  },
}
