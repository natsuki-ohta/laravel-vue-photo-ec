
<script setup>
  import { ref, onMounted } from "vue";
  import { api } from "../libs/api";
  import { cartService } from "../services/cartService";
  import Header from "../components/Header.vue";
  import { useToast } from 'vue-toast-notification';
  import axios from "axios";

  const $toast = useToast();
  const cart = cartService.cart;
  const products = ref([]);
  const categories = ref([]);
  const selectedCategory = ref('');
  const quantities = ref({});
  
  const fetchProducts = async () => {
    const params = {};
    if (selectedCategory.value) {
      params.category_id = selectedCategory.value;
    }
  
    const res = await api.get("/products", { params });
    products.value = res.data.data;
  
    products.value.forEach(p => {
      quantities.value[p.id] = 1;
    });
  };

const getStockQuantity = (product) => {
  return product.stock - getCartQuantity(product);
};
  
const addToCart = (product) => {
  const quantity = quantities.value[product.id];
  if (quantity > product.stock) {
    alert("在庫数を超えています");
    return;
  }
  cartService.setItem({
    id: product.id,
    name: product.name,
    price: product.price,
    stock: product.stock,
    quantity: quantities.value[product.id],
  });

  $toast.success('カートに追加しました', {
    position: 'top',
    duration: 2000,
    dismissible: true,
  });

  quantities.value[product.id] = 1
};


const getCartQuantity = (product) => {
  const item = cart.value.find(i => i.id === product.id);
  return item?.quantity ?? 0;
};

  
onMounted(async () => {
  const categoriesRes = await api.get("/categories");
  categories.value = categoriesRes.data;
  selectedCategory.value = '';
  await fetchProducts();
});
</script>

  
<template>
  <Header />
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="flex flex-col sm:flex-row sm:items-center mb-6 gap-4">
      <h1 class="text-2xl font-bold">
        商品一覧
      </h1>

      <div class="flex items-center gap-2">
        <label class="text-sm text-gray-600">
          カテゴリ
        </label>
        <select
          v-model="selectedCategory"
          @change="fetchProducts"
          class="p-2 border rounded"
        >
          <option value="">すべて</option>
          <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        v-for="product in products"
        :key="product.id"
        class="bg-white p-4 rounded shadow flex flex-col sm:flex-row gap-4"
      >

        <img
          :src="product.image_path || '/no-image.png'"
          alt=""
          class="w-full sm:w-48 h-48 object-cover rounded"
        />

        <div class="flex-1">
          <h2 class="font-bold text-lg">
            {{ product.name }}
          </h2>

          <p class="text-sm text-gray-600">
            {{ product.description }}
          </p>

          <p class=" font-semibold">
            ¥{{ product.price }}
          </p>

          <p class="text-sm text-gray-500">
            在庫 {{ product.stock }}
            <span v-if="getCartQuantity(product) > 0">
              （カート内 {{ getCartQuantity(product) }}）
            </span>
          </p>

          <p class="text-xs text-gray-400">
            残りカート追加可能：{{ getStockQuantity(product) }}
          </p>
          
          <div class="mt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <select
              v-model.number="quantities[product.id]"
              :disabled="getStockQuantity(product) === 0"
              class="p-1 border rounded
                    w-20
                    disabled:bg-gray-100
                    disabled:text-gray-400
                    disabled:cursor-not-allowed"
            >
              <option
                v-for="n in getStockQuantity(product)"
                :key="n"
                :value="n"
              >
                {{ n }}
              </option>
            </select>

            <button
              class="px-3 py-2 rounded bg-black text-white w-full sm:w-auto disabled:bg-gray-400 disabled:cursor-not-allowed text-sm"
              :disabled="getStockQuantity(product) === 0"
              @click="addToCart(product)"
            >
              カートに入れる
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.toast-container {
  position: fixed !important;
  top: 1rem;
  left: 10% !important;
  transform: translateX(-50%);
  z-index: 9999;
}
</style>