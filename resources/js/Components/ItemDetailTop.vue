<script setup lang="ts">
import { computed } from 'vue'

import { Item } from '@/types'

import CommentLink from './CommentLink.vue'
import FavoriteButton from './FavoriteButton.vue'

const props = defineProps<{
  item: Item
  isAuthenticated: boolean
}>()

const endOfSale = computed(() => props.item.is_sold || props.item.seller.is_deleted)
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-3xl font-bold">{{ item.name }}</h2>
    <p class="flex items-center gap-x-2">
      <span class="text-2xl" :class="{ 'line-through': endOfSale }">
        ¥{{ item.price.toLocaleString() }}
      </span>
      <span v-if="endOfSale" class="text-red-500">(販売終了)</span>
    </p>
    <div class="mx-2 flex gap-x-8">
      <FavoriteButton :item="item" :is-authenticated="isAuthenticated" />
      <CommentLink :item="item" />
    </div>
  </div>
</template>
