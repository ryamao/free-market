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
  const message = `以下のコメントを削除します

投稿者: ${props.comment.user?.name}
内容:
${props.comment.content}`

  if (confirm(message)) {
    axios.delete(route('comments.destroy', { comment: props.comment })).then(() => {
      router.reload()
    })
  }
}
</script>

<template>
  <div class="grid grid-cols-[1fr,auto] items-center">
    <div class="flex items-center" :class="{ 'flex-row-reverse': isSeller }">
      <Link
        v-if="comment.user"
        :href="route('users.show', { user: comment.user.id })"
        class="grid grid-cols-[auto,1fr] items-center gap-x-2 hover:opacity-75 focus:opacity-75"
        :class="{ 'grid-cols-[1fr,auto]': isSeller, 'grid-cols-[auto,1fr]': !isSeller }"
      >
        <UserIcon
          :user-id="comment.user.id"
          :user-name="comment.user.name"
          :image-url="comment.user.image_url"
          :force-refresh="true"
          class="size-8 text-sm"
          :class="{ 'order-2': isSeller }"
        />
        <span class="text-base font-bold" :class="{ 'text-gray-300': comment.user.name === null }">
          {{ comment.user.name ?? '(名前未設定)' }}
        </span>
      </Link>
      <span v-if="isSeller" class="mr-auto inline-block text-sm text-gray-400">(出品者)</span>
    </div>

    <DangerButton
      v-if="$page.props.auth.admin"
      class="ml-4 size-fit"
      @click.prevent="deleteComment"
    >
      削除
    </DangerButton>
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
