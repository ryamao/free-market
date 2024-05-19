<script setup lang="ts">
interface Props {
  isFavorite: boolean
}

withDefaults(defineProps<Props>(), {
  isFavorite: false
})

const starPoints = Array.from({ length: 5 })
  .flatMap((_, index) => {
    const r = 90
    const x1 = -Math.sin((index * 72 * Math.PI) / 180) * r
    const y1 = -Math.cos((index * 72 * Math.PI) / 180) * r
    const x2 = -Math.sin(((index * 72 + 36) * Math.PI) / 180) * ((2 * r) / 5)
    const y2 = -Math.cos(((index * 72 + 36) * Math.PI) / 180) * ((2 * r) / 5)
    return `${x1},${y1} ${x2},${y2}`
  })
  .join(' ')
</script>

<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="-100 -100 200 200"
    :fill="isFavorite ? 'currentColor' : 'none'"
    stroke="currentColor"
    class="text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:text-gray-400"
    :class="{ 'text-yellow-400 hover:text-yellow-500 focus:text-yellow-500': isFavorite }"
  >
    <polygon stroke-width="15" stroke-linejoin="round" :points="starPoints" />
  </svg>
</template>
