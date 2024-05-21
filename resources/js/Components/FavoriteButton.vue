<script setup lang="ts">
import { router } from '@inertiajs/vue3'

import { toggleFavorite } from '@/functions'
import { Item } from '@/types'

import FavoriteIcon from './FavoriteIcon.vue'

const props = defineProps<{
  item: Item
  isAuthenticated: boolean
}>()

function onToggleFavorite() {
  toggleFavorite(props.item).then(() => {
    router.reload({ only: ['item'] })
  })
}
</script>

<template>
  <form @submit.prevent="onToggleFavorite">
    <button
      type="submit"
      class="flex w-fit cursor-not-allowed flex-col items-center"
      :class="{
        'cursor-pointer transition duration-150 ease-in-out hover:underline hover:opacity-50 focus:underline focus:opacity-50':
          isAuthenticated
      }"
      :disabled="!isAuthenticated"
      dusk="favorite-button"
    >
      <FavoriteIcon class="size-6" :is-favorite="item.is_favorite" />
      <span class="text-xs" dusk="favorite-count">{{ item.favorite_count }}</span>
    </button>
  </form>
</template>
