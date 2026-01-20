<script setup>
  import { computed } from 'vue'
  import { cartService } from '../services/cartService'
  
  const cart = cartService.cart
  const hasItems = computed(() => cart.value.length > 0)
  </script>
  
  <template>
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <h1 class="text-xl font-bold tracking-wide">
          Photo EC
        </h1>
  
        <router-link
          to="/cart"
          :class="[
            'relative inline-block',
            !hasItems && 'pointer-events-none opacity-40'
          ]"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            class="w-8 h-8"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-5-9v9"
            />
          </svg>
  
          <span 
            v-if="hasItems"
            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
          >
            {{ cart.length }}
          </span>
        </router-link>
      </div>
    </header>
  </template>
  