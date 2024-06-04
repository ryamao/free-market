<script setup lang="ts">
import { Item } from '@/types'

import CommentIcon from './CommentIcon.vue'
import FavoriteIcon from './FavoriteIcon.vue'

defineProps<{
  item?: Item
}>()
</script>

<template>
  <template v-if="item !== undefined">
    <div>
      <div class="rounded-t-lg bg-gray-100">
        <img :src="item.image_url" alt="" class="aspect-[4/3] w-full rounded-t-lg object-contain" />
      </div>
      <div class="space-y-1 p-1">
        <h3 class="text-lg font-bold">{{ item.name }}</h3>
        <div class="flex items-center justify-between">
          <span class="space-x-2 text-base">
            <span :class="{ 'line-through': item.is_sold }"
              >¥{{ item.price.toLocaleString() }}</span
            >
            <span v-if="item.is_sold" class="text-sm text-gray-500">(販売終了)</span>
          </span>
          <div class="flex items-center gap-x-2">
            <div class="flex items-center gap-x-0.5">
              <FavoriteIcon
                class="size-4"
                :class="{ 'hover:text-gray-500': $page.props.auth.user === null }"
                :is-favorite="item.is_favorite"
              />
              <span class="text-xs">{{ item.favorite_count }}</span>
            </div>
            <div class="flex items-center gap-x-0.5">
              <CommentIcon class="size-4" />
              <span class="text-xs">{{ item.comment_count }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <template v-else>
    <div class="rounded-lg border">
      <div class="aspect-[4/3] w-full animate-pulse rounded-t-lg bg-gray-300"></div>
      <div class="space-y-1 p-1">
        <h3 class="w-1/2 animate-pulse rounded bg-gray-300 text-lg font-bold text-transparent">
          -
        </h3>
        <div class="grid grid-cols-2">
          <span class="w-1/2 animate-pulse rounded bg-gray-300 text-base text-transparent">-</span>
          <div class="flex justify-end gap-x-2">
            <div class="w-8 animate-pulse rounded bg-gray-300 text-transparent">-</div>
            <div class="w-8 animate-pulse rounded bg-gray-300 text-transparent">-</div>
          </div>
        </div>
      </div>
    </div>
  </template>
</template>
