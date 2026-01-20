<script setup>
  import { ref, onMounted } from "vue";
  import { api } from "../../libs/api";
  import { useToast } from 'vue-toast-notification';
  
  const toast = useToast();
  
  const categories = ref([]);
  const selectedCategory = ref('');
  const products = ref([]);
  const showCreateModal = ref(false);
  
  const newProduct = ref({
    name: '',
    description: '',
    stock: 0,
    price: 0,
    category_id: '',
    imageFile: null,
    imagePreview: null,
  });
  
  const onNewImageChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
  
    newProduct.value.imageFile = file;
    newProduct.value.imagePreview = URL.createObjectURL(file);
  };
  
  const onImageChange = (e, product) => {
    const file = e.target.files[0];
    if (!file) return;
  
    product.imageFile = file;
    product.imagePreview = URL.createObjectURL(file);
  };
  
  const updateImage = async (product) => {
    const formData = new FormData();
    formData.append('image', product.imageFile);
    formData.append('product_id', product.id);
  
    const res = await api.post(`/admin/products/${product.id}/image`, formData);
    product.image_path = res.data.image_path;
    product.imagePreview = null;
    product.imageFile = null;
  };
  
  const fetchProducts = async () => {
    const params = {};
    if (selectedCategory.value) {
      params.category_id = selectedCategory.value;
    }
  
    const res = await api.get("/products", { params });
    products.value = res.data.data;
  };
  
  const deleteProduct = async (product) => {
    if (!confirm(`「${product.name}」を削除しますか？`)) return;
  
    await api.post(`/admin/products/${product.id}/delete`);
    products.value = products.value.filter(p => p.id !== product.id);
  };
  
  const updateAll = async () => {
    if (!window.confirm('商品情報を一括保存します(画像は各商品の保存ボタンから登録してください)')) return
    for (const [index, product] of products.value.entries()) {
      if (!product.name?.trim()) {
        toast.error(`【${index + 1}行目】商品名は必須です`, { position: 'top' });
        return;
      }
  
      if (!product.category_id) {
        toast.error(`【${index + 1}行目】カテゴリを選択してください`, { position: 'top' });
        return;
      }
  
      if (product.stock == null || product.stock < 0) {
        toast.error(`【${index + 1}行目】個数は0以上で指定してください`, { position: 'top' });
        return;
      }
  
      if (product.price == null || product.price < 1) {
        toast.error(`【${index + 1}行目】価格は1以上で指定してください`, { position: 'top' });
        return;
      }
    }
  
    try {
      await api.put('/admin/products/update-all', {
        products: products.value,
      });
  
      toast.success('一括更新しました', {
        position: 'top',
        duration: 2000,
      });
  
      await fetchProducts();
    } catch {
      toast.error('更新失敗しました', { position: 'top' });
    }
  };

  const createProduct = async () => {
    if (!newProduct.value.name?.trim()) {
      toast.error('商品名は必須です', { position: 'top' });
      return;
    }
  
    if (!newProduct.value.category_id) {
      toast.error('カテゴリを選択してください', { position: 'top' });
      return;
    }
  
    if (newProduct.value.stock < 1) {
      toast.error('在庫は1以上で指定してください', { position: 'top' });
      return;
    }
  
    if (newProduct.value.price < 1) {
      toast.error('価格は1以上でしてください', { position: 'top' });
      return;
    }
  
    if (!newProduct.value.imageFile) {
      toast.error('画像は必須です', {
        position: 'top',
        duration: 2000,
      });
      return;
    }
  
    const formData = new FormData();
    formData.append('name', newProduct.value.name);
    formData.append('description', newProduct.value.description);
    formData.append('category_id', newProduct.value.category_id);
    formData.append('stock', newProduct.value.stock);
    formData.append('price', newProduct.value.price);
    formData.append('image', newProduct.value.imageFile);
  
    try {
      const res = await api.post('/admin/products', formData);
      products.value.push(res.data.data);
      showCreateModal.value = false;
  
      toast.success('商品を登録しました', {
        position: 'top',
        duration: 2000,
      });
    } catch {
      toast.error('登録に失敗しました', { position: 'top' });
    }
  };
  
  onMounted(async () => {
    const res = await api.get('/categories');
    categories.value = res.data;
  
    await fetchProducts();
  });
  </script>
  
  
<template>
  <div class="header">
    <div v-if="showCreateModal" class="modal-backdrop">
      <div class="modal">
        商品名
        <input v-model="newProduct.name" placeholder="商品名" />
        商品説明
        <textarea
          v-model="newProduct.description"
          placeholder="商品説明"
        ></textarea>

          <div class="header filter-bar">
            <div class="filter-item">
              <label>カテゴリ</label>
              <select
                v-model="newProduct.category_id"
                class="border rounded p-2 category-select"
              >
                <option
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
            </div>
        
          <div>
            <label class="block text-xs text-gray-600 mb-1">
              個数
            </label>
            <input
              type="number"
              v-model.number="newProduct.stock"
              class="border rounded p-2 w-20 text-right"
              min="0"
            />
          </div>

          <div>
            <label class="block text-xs text-gray-600 mb-1">
              価格
            </label>
            <input
              type="number"
              v-model.number="newProduct.price"
              class="border rounded p-2 w-28 text-right"
              min="0"
            />
          </div>
        </div>

        <input
          type="file"
          accept="image/*"
          @change="onNewImageChange"
        />

        <img
          v-if="newProduct.imagePreview"
          :src="newProduct.imagePreview"
          class="create-preview"
        />

        <div class="actions">
          <button @click="showCreateModal = false">キャンセル</button>
          <button class="btn main" @click="createProduct">
            保存
          </button>
        </div>
      </div>
    </div>

    <h1 class="title">在庫管理</h1>

    <div class="actions">
    <button class="btn create ml-2" @click="showCreateModal = true">
      ＋ 新商品登録
    </button>

      <button class="btn main ml-2" @click="updateAll">
        変更を一括保存
      </button>
    </div>
  </div>

  <div class="header">
    <div class="flex flex-row items-center gap-3 category-filter">
      <label class="text-sm font-medium shrink-0">
        カテゴリ
      </label>
    
      <select
        v-model="selectedCategory"
        @change="fetchProducts"
        class="border rounded p-2 category-select"
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



  <table class="table">
    <thead>
      <tr>
        <th>画像</th>
        <th>商品情報</th>
        <th>在庫</th>
        <th>価格</th>
        <th>削除</th>
      </tr>
    </thead>

    <tbody>
        <tr
          v-for="product in products"
          :key="product.id ?? product._tempId"
          :class="{ new: product.isNew }"
        >
        <td>
          <div class="image-area">
            <img
              :src="product.imagePreview || product.image_path"
              class="thumb"
            />

            <label class="image-edit">
              変更
              <input
                type="file"
                hidden
                @change="onImageChange($event, product)"
              />
            </label>

            <button
              class="image-save"
              @click="updateImage(product)"
              :disabled="!product.imageFile"
            >
              保存
            </button>
          </div>
        </td>

        <td>
          <div class="product-info space-y-2">

            <div class="field">
              <span>商品名</span>
              <input v-model="product.name" />
            </div>
          
            <div class="field">
              <span>商品説明</span>
              <textarea
                v-model="product.description"
                rows="2"
              ></textarea>
            </div>
          
            <div class="field">
              <span>カテゴリ</span>
              <select
                v-model="product.category_id"
                class="border rounded p-1 w-full"
              >
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
        </td>

        <td>
          <input
            type="number"
            v-model.number="product.stock"
            class="num"
            min="0"
          />
        </td>

        <td>
          <input
            type="number"
            v-model.number="product.price"
            class="num"
            min="0"
          />
        </td>

        <td class="right">
          <button class="btn danger" @click="deleteProduct(product)">
            削除
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style>
.header {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
}

.title {
  margin: 0;
}

.top-actions {
  margin-bottom: 12px;
  text-align: right;
}

.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;

  display: block;
  overflow-x: auto;
  white-space: nowrap;
}

th,
td {
  border: 1px solid #ddd;
  padding: 10px;
  vertical-align: top;
}

th {
  background: #f3f3f3;
}

tbody tr {
  background: #fff;
}

tbody tr:nth-child(even) {
  background: #fcfcfa;
}

tr.new {
  background:rgb(251, 235, 222);
}

.image-area {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  min-width: 90px;
}

.thumb {
  width: 72px;
  height: 54px;
  min-width: 72px;
  min-height: 54px;
  object-fit: cover;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.image-edit {
  font-size: 11px;
  color: #555;
  cursor: pointer;
}

.image-save {
  font-size: 11px;
  padding: 3px 6px;
  background: #f5f5f5;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
}

.image-save:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.product-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.field span {
  font-size: 11px;
  color: #666;
}

.product-info input {
  width: 200px;
}

.product-info textarea {
  width: 280px;
  resize: vertical;
  font-size: 12px;
}

input,
textarea {
  background: #fff;
  border: 1px solid #bbb;
  padding: 6px 8px;
  border-radius: 4px;
  font-size: 13px;
}

input:hover,
textarea:hover {
  border-color: #999;
}

input:focus,
textarea:focus {
  border-color: #333;
  outline: none;
}

.num {
  width: 80px;
  text-align: right;
}

.btn {
  padding: 6px 10px;
  font-size: 12px;
  cursor: pointer;
  border: none;
  border-radius: 4px;
}

.btn.main {
  background: #333;
  color: #fff;
}
.btn.create {
  background:rgb(255, 131, 37);
  color: #fff;
}

.btn.danger {
  background: #c0392b;
  color: #fff;
}

.right {
  text-align: right;
}

@media (max-width: 768px) {
  th,
  td {
    padding: 8px;
  }

  .product-info textarea {
    width: 220px;
  }

  .product-info input {
    width: 160px;
  }

  .num {
    width: 70px;
  }
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal {
  background: #fff;
  width: 90%;
  max-width: 520px;
  max-height: 90vh;
  overflow-y: auto;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(0,0,0,.25);
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.modal h2 {
  margin: 0 0 8px;
  font-size: 18px;
}

.modal input,
.modal textarea {
  width: 100%;
}

.modal .actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  margin-top: 12px;
}

@media (max-width: 600px) {
  .modal {
    padding: 16px;
    border-radius: 6px;
  }

  .modal h2 {
    font-size: 16px;
  }
}

.toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  background: #333;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  font-size: 13px;
  box-shadow: 0 4px 12px rgba(0,0,0,.25);
  z-index: 1000;
  animation: fadeInOut 2s ease forwards;
}

@keyframes fadeInOut {
  0% { opacity: 0; transform: translateY(6px); }
  10% { opacity: 1; transform: translateY(0); }
  90% { opacity: 1; }
  100% { opacity: 0; }
}

@media (max-width: 600px) {
  .toast {
    right: 50%;
    transform: translateX(50%);
    bottom: 16px;
  }
}

.create-preview {
  margin-top: 8px;
  width: 100%;
  max-height: 160px;
  object-fit: contain;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: #fafafa;
}
</style>
