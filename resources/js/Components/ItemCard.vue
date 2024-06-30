<script setup lang="ts">
import { Item } from '@/types'

import CommentIcon from './CommentIcon.vue'
import FavoriteIcon from './FavoriteIcon.vue'

defineProps<{
  item?: Item
}>()
</script>

<template>
  <div class="flex h-32 gap-x-4 sm:h-full sm:flex-col sm:gap-y-2">
    <template v-if="item !== undefined">
      <div class="aspect-[4/3] h-32 rounded-lg bg-gray-100 sm:size-full sm:rounded-b-none">
        <img
          :src="item.image_url"
          alt=""
          class="aspect-[4/3] size-full rounded-t-lg object-contain"
        />
      </div>
      <div
        class="grid w-full grid-rows-3 content-evenly items-center justify-between py-1 sm:grid-cols-2 sm:grid-rows-2 sm:py-0"
      >
        <h3 class="text-lg font-bold sm:col-span-2">{{ item.name }}</h3>
        <div class="space-x-2 text-base">
          <span :class="{ 'line-through': item.is_sold }">¥{{ item.price.toLocaleString() }}</span>
          <span v-if="item.is_sold" class="text-sm text-gray-500">(販売終了)</span>
        </div>
        <div class="flex items-center gap-x-2 sm:justify-end">
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
    </template>

    <template v-else>
      <div
        class="aspect-[4/3] animate-pulse rounded-lg bg-gray-300 sm:w-full sm:rounded-b-none"
      ></div>
      <div
        class="grid w-full grid-rows-[repeat(3,1.5rem)] content-evenly gap-y-1 sm:grid-cols-2 sm:grid-rows-[repeat(2,1.5rem)] sm:content-normal"
      >
        <h3
          class="w-1/2 min-w-24 animate-pulse rounded bg-gray-300 text-lg font-bold text-transparent sm:col-span-2"
        >
          -
        </h3>
        <div class="w-1/4 min-w-16 animate-pulse rounded bg-gray-300 text-base text-transparent">
          -
        </div>
        <div class="flex gap-x-2 sm:justify-end">
          <div class="w-8 animate-pulse rounded bg-gray-300 text-transparent">-</div>
          <div class="w-8 animate-pulse rounded bg-gray-300 text-transparent">-</div>
        </div>
      </div>
    </template>
  </div>
</template>
