<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { useIntersectionObserver } from '@vueuse/core'
import axios from 'axios'
import { ref } from 'vue'

import ItemCard from '@/Components/ItemCard.vue'
import { Item, Paginator } from '@/types'

const props = defineProps<{
  items: Paginator<Item>
  nextUrl: (nextPage: number) => string
}>()

const allItems = ref<Item[]>(props.items.data)
const currentPage = ref<number>(props.items.meta.current_page)
const lastPage = ref<number>(props.items.meta.last_page)

const lastItemRef = ref<HTMLElement | null>(null)

useIntersectionObserver(lastItemRef, ([{ isIntersecting }]) => {
  if (!isIntersecting || currentPage.value >= lastPage.value) {
    return
  }

  axios.get(props.nextUrl(currentPage.value + 1)).then(({ data }) => {
    allItems.value = [...allItems.value, ...data.data]
    currentPage.value = data.meta.current_page
    lastPage.value = data.meta.last_page
  })
})
</script>

<template>
  <ul dusk="item-list" class="grid grid-cols-[repeat(auto-fill,minmax(16rem,1fr))] gap-8">
    <li v-for="item in allItems" :key="item.id">
      <Link :href="route('items.show', { item: item })">
        <ItemCard :item="item" />
      </Link>
    </li>

    <li v-if="currentPage < lastPage" ref="lastItemRef">
      <ItemCard />
    </li>
  </ul>
</template>
