<script setup lang="ts">
const r = 80
const x1 = Math.sin(Math.PI + Math.PI / 4 + Math.PI / 16) * r
const y1 = -Math.cos(Math.PI + Math.PI / 4 + Math.PI / 16) * r
const x2 = Math.sin(Math.PI + Math.PI / 4 - Math.PI / 16) * r
const y2 = -Math.cos(Math.PI + Math.PI / 4 - Math.PI / 16) * r
const commentIconPath = `M ${x1} ${y1} A 1 1.75 45 0 0 ${x2} ${y2} A 80 80 0 1 0 ${x1} ${y1} Z`
</script>

<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="-100 -100 200 200"
    fill="none"
    stroke="currentColor"
    class="text-gray-500"
  >
    <path stroke-width="15" :d="commentIconPath" />
    <circle cx="-33" cy="0" r="10" fill="currentColor" />
    <circle cx="0" cy="0" r="10" fill="currentColor" />
    <circle cx="33" cy="0" r="10" fill="currentColor" />
  </svg>
</template>
