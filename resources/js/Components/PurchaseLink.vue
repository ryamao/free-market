<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import { Item } from '@/types'

defineProps<{
  item: Item
  isAuthenticated: boolean
}>()
</script>

<template>
  <Link
    v-if="isAuthenticated"
    :href="route('purchase.create', { item: item })"
    class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-emerald-600 px-4 py-0.5 text-lg font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800"
  >
    購入する
  </Link>
  <span
    v-else
    class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-md border border-transparent bg-gray-300 px-4 py-0.5 text-lg font-semibold uppercase tracking-widest text-white"
  >
    購入する
  </span>
</template>
