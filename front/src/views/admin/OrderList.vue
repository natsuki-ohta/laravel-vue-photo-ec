<script setup>
  import { ref, onMounted } from 'vue';
import { api } from '../../libs/api';

const orders = ref([]);

const fetchOrders = async () => {
  const res = await api.get('/admin/order');
  orders.value = res.data.data;
};

onMounted(fetchOrders);
</script>

<template>
  <div class="header">
    <h1 class="title">注文一覧</h1>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>注文ID</th>
        <th>注文日時</th>
        <th>注文者</th>
        <th>支払い方法</th>
        <th>点数</th>
        <th>合計金額</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="order in orders" :key="order.id">
        <td>#{{ order.id }}</td>
        <td>{{ new Date(order.created_at).toLocaleString() }}</td>
        <!-- 注文者 -->
        <td>
          <div class="customer">
            <div class="name">{{ order.customer.name }}</div>
            <div class="sub">
              {{ order.customer.tel }}<br />
              {{ order.customer.postal }} {{ order.customer.address }}
            </div>
          </div>
        </td>

        <td>{{ order.payment_method }}</td>
        <td>{{ order.total_quantity }} 点</td>
        <td>¥{{ order.total_price.toLocaleString() }}</td>

        <td>
          <router-link :to="`/admin/order/${order.id}`">
            注文詳細へ
          </router-link>
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

.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
}

th {
  background: #f3f3f3;
}

tbody tr:nth-child(even) {
  background: #fafafa;
}

.customer .name {
  font-weight: bold;
  font-size: 13px;
}

.customer .sub {
  font-size: 11px;
  color: #666;
  line-height: 1.4;
}

</style>