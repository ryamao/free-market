<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import dayjs from 'dayjs'

import UserIcon from '@/Components/UserIcon.vue'
import { Comment } from '@/types'

import DangerButton from './DangerButton.vue'

const props = defineProps<{
  comment: Comment
  isSeller: boolean
}>()

function deleteComment() {
  if (confirm('本当に削除しますか？')) {
    axios.delete(route('comments.destroy', { comment: props.comment })).then(() => {
      router.reload()
    })
  }
}
</script>

<template>
  <div class="grid grid-cols-[1fr,auto] gap-x-8">
    <div class="flex items-center" :class="{ 'flex-row-reverse': isSeller }">
      <Link
        href="#"
        class="flex items-center gap-x-2 hover:opacity-75 focus:opacity-75"
        :class="{ 'flex-row-reverse': isSeller }"
      >
        <UserIcon
          :user-id="comment.user.id"
          :user-name="comment.user.name"
          :image-url="comment.user.image_url"
          :force-refresh="true"
          class="size-8 text-sm"
        />
        <span
          class="text-base font-bold"
          :class="{ 'text-gray-300': comment.user.name === null }"
          >{{ comment.user.name ?? '(名前未設定)' }}</span
        >
      </Link>
      <span v-if="isSeller" class="mr-auto inline-block text-sm text-gray-400">(出品者)</span>
    </div>

    <DangerButton v-if="$page.props.auth.admin" @click.prevent="deleteComment">削除</DangerButton>
  </div>

  <div class="mt-1 space-y-2 rounded bg-gray-50 p-1">
    <p class="break-words">
      <template v-for="(line, index) in comment.content.split(/\n/)" :key="index">
        <span>{{ line }}</span>
        <br />
      </template>
    </p>
    <p class="text-right text-xs text-gray-400">
      {{ dayjs(comment.created_at).format('YYYY/MM/DD HH:mm:ss') }}
    </p>
  </div>
</template>
