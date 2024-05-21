<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import { Comment, UserData } from '@/types'

defineProps<{
  comment: Comment
  isSeller: boolean
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
  <div class="flex items-center" :class="{ 'flex-row-reverse': isSeller }">
    <Link
      href="#"
      class="flex gap-x-2 hover:opacity-75 focus:opacity-75"
      :class="{ 'flex-row-reverse': isSeller }"
    >
      <img
        v-if="comment.user.image_url"
        :src="comment.user.image_url"
        alt=""
        class="size-6 rounded-full"
      />
      <div
        v-else
        class="flex size-6 items-center justify-center rounded-full text-sm text-white after:content-[attr(data-text)]"
        :class="getUserColor(comment.user)"
        :data-text="comment.user.name?.[0]"
      ></div>
      <span class="text-base font-bold" :class="{ 'text-gray-300': comment.user.name === null }">{{
        comment.user.name ?? '(名前未設定)'
      }}</span>
    </Link>
    <span v-if="isSeller" class="mr-auto inline-block text-sm text-gray-400">(出品者)</span>
  </div>

  <div class="mt-1 space-y-2 rounded bg-gray-50 p-1">
    <p class="break-words">
      <template v-for="(line, index) in comment.content.split(/\n/)" :key="index">
        <span>{{ line }}</span>
        <br />
      </template>
    </p>
    <p class="text-right text-xs text-gray-400">{{ comment.created_at }}</p>
  </div>
</template>
