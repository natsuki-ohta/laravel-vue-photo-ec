<script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import { api } from '../../libs/api';
  
  const route = useRoute();
  
  /**
   * 初期値は「完成形」
   * 非同期でも template が壊れない
   */
  const order = ref({
    customer: {
      name: '',
      kana: '',
      tel: '',
      address: '',
    },
    items: [],
  });
  
  const loading = ref(true);
  
  const fetchOrder = async () => {
    try {
      const res = await api.get(`/admin/order/${route.params.id}`);
      order.value = res.data.data;
    } finally {
      loading.value = false;
    }
  };
  
  onMounted(fetchOrder);
  </script>

<template>
  <h2>注文詳細</h2>

  <!-- 注文者情報 -->
  <div class="order-info">
    <div class="info-row">
      <span class="label">氏名</span>
      <span>{{ order.customer.name }}</span>
    </div>
    <div class="info-row">
      <span class="label">フリガナ</span>
      <span>{{ order.customer.kana }}</span>
    </div>
    <div class="info-row">
      <span class="label">電話番号</span>
      <span>{{ order.customer.tel }}</span>
    </div>
    <div class="info-row">
      <span class="label">住所</span>
      <span>{{ order.customer.address }}</span>
    </div>
  </div>

  <!-- 注文商品 -->
  <table class="table">
    <thead>
      <tr>
        <th>商品名</th>
        <th>単価</th>
        <th>数量</th>
        <th>小計</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="item in order.items" :key="item.product_id">
        <td>{{ item.product_name }}</td>
        <td>¥{{ item.price.toLocaleString() }}</td>
        <td>{{ item.quantity }}</td>
        <td>¥{{ item.subtotal.toLocaleString() }}</td>
      </tr>

      <tr v-if="!order.items.length">
        <td colspan="4" class="empty">
          商品がありません
        </td>
      </tr>
    </tbody>
  </table>
</template>



<style scoped>
  /* ===== 注文者情報 ===== */
  .order-info {
    margin-bottom: 20px;
    padding: 12px 16px;
    border: 1px solid #ddd;
    background: #fafafa;
    border-radius: 6px;
    font-size: 14px;
  }
  
  .info-row {
    display: flex;
    gap: 12px;
    margin-bottom: 6px;
  }
  
  .info-row:last-child {
    margin-bottom: 0;
  }
  
  .label {
    width: 90px;
    font-weight: 600;
    color: #555;
  }
  
  /* ===== テーブル ===== */
  .table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background: #fff;
  }
  
  th,
  td {
    border: 1px solid #ddd;
    padding: 10px;
  }
  
  th {
    background: #f3f3f3;
    text-align: left;
  }
  
  tbody tr:nth-child(even) {
    background: #fafafa;
  }
  
  .empty {
    text-align: center;
    color: #888;
    padding: 16px;
  }
  </style>
  


<style scoped>
/* ===== 注文者情報 ===== */
.order-info {
  margin-bottom: 20px;
  padding: 12px 16px;
  border: 1px solid #ddd;
  background: #fafafa;
  border-radius: 6px;
  font-size: 14px;
}

.info-row {
  display: flex;
  gap: 12px;
  margin-bottom: 6px;
}

.info-row:last-child {
  margin-bottom: 0;
}

.label {
  width: 90px;
  font-weight: 600;
  color: #555;
}

</style>