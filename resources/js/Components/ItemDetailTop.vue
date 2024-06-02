<script setup lang="ts">
import { Item } from '@/types'

import CommentLink from './CommentLink.vue'
import FavoriteButton from './FavoriteButton.vue'

defineProps<{
  item: Item
  isAuthenticated: boolean
}>()
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-3xl font-bold">{{ item.name }}</h2>
    <p class="flex items-center gap-x-2">
      <span class="text-2xl" :class="{ 'line-through': item.is_sold }">
        ¥{{ item.price.toLocaleString() }}
      </span>
      <span v-if="item.is_sold" class="text-red-500">(販売終了)</span>
    </p>
    <div class="mx-2 flex gap-x-8">
      <FavoriteButton :item="item" :is-authenticated="isAuthenticated" />
      <CommentLink :item="item" />
    </div>
  </div>
</template>
