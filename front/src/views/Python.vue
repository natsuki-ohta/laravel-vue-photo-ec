<template>
  <div class="python">
    <h1>Python / Django デモアプリ</h1>

    <p>
      Python（Django）を用いて、地図表示・API連携を実装したデモページです。
    </p>

    <ul>
      <li>Django REST Framework</li>
      <li>Google Maps API 連携</li>
      <li>Vue + Django の分離構成</li>
    </ul>

    <!-- Google Map -->
    <div ref="mapRef" class="map"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const mapRef = ref(null)
let map = null

const initMap = async () => {
  // マップ初期化
  map = new google.maps.Map(mapRef.value, {
    center: { lat: 35.681236, lng: 139.767125 }, // 東京駅
    zoom: 12,
  })

  // Django API から商品取得
  const res = await axios.get('http://localhost:8001/api/products/')
  const products = res.data

  products.forEach(product => {
    if (!product.latitude || !product.longitude) return

    const marker = new google.maps.Marker({
      position: {
        lat: Number(product.latitude),
        lng: Number(product.longitude),
      },
      map,
      title: product.name,
    })

    const infoWindow = new google.maps.InfoWindow({
      content: `
        <div style="max-width:200px">
          <h3>${product.name}</h3>
          ${
            product.image_path
              ? `<img src="${product.image_path}" style="width:100%" />`
              : ''
          }
        </div>
      `,
    })

    marker.addListener('click', () => {
      infoWindow.open(map, marker)
    })
  })
}

const loadGoogleMap = () => {
  if (window.google) {
    initMap()
    return
  }

  const script = document.createElement('script')
  script.src =
    'https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAP_API_KEY'
  script.async = true
  script.onload = initMap
  document.head.appendChild(script)
}

onMounted(() => {
  loadGoogleMap()
})
</script>

<style scoped>
.python {
  padding: 24px;
}

.map {
  width: 100%;
  height: 500px;
  margin-top: 20px;
  border-radius: 8px;
}
</style>
