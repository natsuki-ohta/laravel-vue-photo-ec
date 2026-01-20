<template>
  <div class="dashboard">
    <h1 class="title">管理画面</h1>

    <div class="menu">
      <router-link to="/admin/products" class="menu-card">
        <h2>在庫管理</h2>
        <p>商品の追加・編集・削除、在庫数の管理</p>
      </router-link>

      <router-link to="/admin/order" class="menu-card">
        <h2>注文履歴</h2>
        <p>注文一覧・注文ステータスの確認</p>
      </router-link>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
.dashboard {
  padding: 32px;
}

.title {
  font-size: 22px;
  margin-bottom: 32px;
}

.menu {
  display: flex;
  gap: 24px;
}

.menu-card {
  flex: 1;
  padding: 24px;
  border: 1px solid #ddd;
  border-radius: 8px;
  text-decoration: none;
  color: #333;
  background: #fff;
}

.menu-card h2 {
  font-size: 18px;
  margin-bottom: 8px;
}

.menu-card p {
  font-size: 14px;
  color: #666;
}

.menu-card:hover {
  background: #f5f5f5;
}
</style>