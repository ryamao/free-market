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
    <p class="text-2xl">¥{{ item.price.toLocaleString() }}</p>
    <div class="mx-2 flex gap-x-8">
      <FavoriteButton :item="item" :is-authenticated="isAuthenticated" />
      <CommentLink :item="item" />
    </div>
  </div>
</template>
