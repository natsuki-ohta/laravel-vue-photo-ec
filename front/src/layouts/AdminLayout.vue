<script setup>
  import axios from '../libs/axios'
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  
  const logout = async () => {
    await axios.post('/logout')
    router.push('/admin/login')
  }
</script>

<template>
  <div class="admin">
    <header class="header">
      <div>管理画面</div>
      <button @click="logout">ログアウト</button>
    </header>

    <main class="main">
      <router-view />
    </main>
  </div>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  padding: 12px;
  background: #f3f4f6;
}
.main {
  padding: 16px;
}
</style>
