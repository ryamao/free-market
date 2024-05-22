<script setup lang="ts">
import { UserData } from '@/types'

defineProps<{
  user: UserData
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

function getUserColor(user: UserData) {
  return colorPalette[user.id % colorPalette.length]
}
</script>

<template>
  <div>
    <img
      v-if="user.image_url"
      :src="user.image_url"
      alt=""
      class="size-full rounded-full object-cover"
    />
    <div
      v-else
      class="flex size-full items-center justify-center rounded-full text-white after:content-[attr(data-text)]"
      :class="getUserColor(user)"
      :data-text="user.name?.[0]"
    ></div>
  </div>
</template>
