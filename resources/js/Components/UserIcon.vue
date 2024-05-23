<script setup lang="ts">
const props = defineProps<{
  userId: number
  userName: string | null
  imageUrl: string | null
  forceRefresh: boolean
}>()

const colorPalette = [
  'bg-gray-500',
  'bg-red-500',
  'bg-orange-500',
  'bg-amber-500',
  'bg-yellow-500',
  'bg-lime-500',
  'bg-green-500',
  'bg-emerald-500',
  'bg-teal-500',
  'bg-cyan-500',
  'bg-sky-500',
  'bg-blue-500',
  'bg-indigo-500',
  'bg-violet-500',
  'bg-purple-500',
  'bg-fuchsia-500',
  'bg-pink-500',
  'bg-rose-500'
]

const userColor = colorPalette[props.userId % colorPalette.length]
</script>

<template>
  <div>
    <img
      v-if="imageUrl"
      :src="imageUrl + (forceRefresh ? '?timestamp=' + new Date().getTime() : '')"
      alt=""
      class="size-full rounded-full object-cover"
    />
    <div
      v-else
      class="flex size-full items-center justify-center rounded-full text-white after:content-[attr(data-text)]"
      :class="userColor"
      :data-text="userName?.[0]"
    ></div>
  </div>
</template>
