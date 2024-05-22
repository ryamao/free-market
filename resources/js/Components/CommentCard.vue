<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import UserIcon from '@/Components/UserIcon.vue'
import { Comment } from '@/types'

defineProps<{
  comment: Comment
  isSeller: boolean
}>()
</script>

<template>
  <div class="flex items-center" :class="{ 'flex-row-reverse': isSeller }">
    <Link
      href="#"
      class="flex gap-x-2 hover:opacity-75 focus:opacity-75"
      :class="{ 'flex-row-reverse': isSeller }"
    >
      <UserIcon :user="comment.user" class="size-6 text-sm" />
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
