<template>
  <div class="portfolio">
    <h1 class="title">Portfolio Photo EC</h1>
    <p class="subtitle">
      写真販売ECサイト（Vue + Laravel）
    </p>

    <div class="menu">
      <router-link to="/home" class="menu-card">
        <h2>ECサイトへ</h2>
        <p>商品閲覧・カート・購入フロー</p>
      </router-link>

      <router-link to="/admin/login" class="menu-card">
        <h2>管理画面ログインへ</h2>
        <p>商品管理・在庫管理・注文確認</p>
      </router-link>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
.portfolio {
  min-height: 100vh;
  padding: 40px 20px;
  background: #fafafa;
}

.title {
  text-align: center;
  font-size: 28px;
  margin-bottom: 8px;
}

.subtitle {
  text-align: center;
  color: #666;
  margin-bottom: 40px;
  font-size: 14px;
}

.menu {
  display: flex;
  gap: 24px;
  max-width: 900px;
  margin: 0 auto;
}

.menu-card {
  flex: 1;
  padding: 32px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  text-decoration: none;
  color: #333;
  transition: all .2s ease;
}

.menu-card h2 {
  font-size: 20px;
  margin-bottom: 8px;
}

.menu-card p {
  font-size: 14px;
  color: #666;
}

.menu-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,.08);
}

@media (max-width: 768px) {
  .menu {
    flex-direction: column;
  }
}
</style>
