<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import { Item } from '@/types'

import CommentIcon from './CommentIcon.vue'

defineProps<{
  item: Item
}>()
</script>

<template>
  <Link
    :href="route('comments.index', { item: item })"
    class="flex w-fit cursor-pointer flex-col items-center transition duration-150 ease-in-out hover:underline hover:opacity-50 focus:underline focus:opacity-50"
  >
    <CommentIcon class="size-6" />
    <span class="text-xs">{{ item.comment_count }}</span>
  </Link>
</template>
