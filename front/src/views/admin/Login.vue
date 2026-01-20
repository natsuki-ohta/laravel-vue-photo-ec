<script setup>
  import { ref } from 'vue'
  import axios from '../../libs/axios'
  import { useRouter } from 'vue-router'
  
  const email = ref('admin@example.com')
  const password = ref('')
  const error = ref('')
  const loading = ref(false)
  const router = useRouter()
  
  const handleLogin = async () => {
    error.value = ''
    loading.value = true
  
    try {
      await axios.get('/sanctum/csrf-cookie', {
        baseURL: '',
      })
      await axios.post('/login', {
        email: email.value,
        password: password.value,
      })
      router.push('/admin')
    } catch {
      error.value = 'メールアドレスまたはパスワードが正しくありません'
    } finally {
      loading.value = false
    }
  }
  </script>

<template>
  <div class="login-wrapper">
    <h1>管理画面ログイン</h1>

    <form @submit.prevent="handleLogin">
      <div class="field">
        <label>メールアドレス</label>
        <input
          v-model="email"
          type="email"
          placeholder="admin@example.com"
          required
        />
      </div>

      <div class="field">
        <label>パスワード</label>
        <input
          v-model="password"
          type="password"
          placeholder="password"
          required
        />
      </div>

      <p v-if="error" class="error">{{ error }}</p>

      <button :disabled="loading">
        {{ loading ? 'ログイン中...' : 'ログイン' }}
      </button>
    </form>
  </div>
</template>

<style scoped>
.login-wrapper {
  max-width: 360px;
  margin: 80px auto;
  padding: 24px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: #fff;
}

h1 {
  margin-bottom: 24px;
  text-align: center;
}

.field {
  margin-bottom: 16px;
}

label {
  display: block;
  margin-bottom: 4px;
  font-size: 14px;
}

input {
  width: 100%;
  padding: 8px;
  border: 1px solid #999;
  border-radius: 4px;
  background: #fff;
  color: #000;
}

button {
  width: 100%;
  padding: 10px;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 4px;
}

button:disabled {
  opacity: 0.6;
}

.error {
  color: red;
  margin-bottom: 8px;
}
</style>

