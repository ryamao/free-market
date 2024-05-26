<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { useIntersectionObserver } from '@vueuse/core'
import axios from 'axios'
import { ref } from 'vue'

import ItemCard from '@/Components/ItemCard.vue'
import { Item } from '@/types'

const props = defineProps<{
  nextUrl: (nextPage: number) => string
}>()

const items = ref<Item[]>([])
const nextPage = ref<number>(1)
const lastPage = ref<number>(1)

const listBottomRef = ref<HTMLElement | null>(null)

useIntersectionObserver(listBottomRef, ([{ isIntersecting }]) => {
  if (!isIntersecting || nextPage.value > lastPage.value) {
    return
  }

  let url = props.nextUrl(nextPage.value)

  axios.get(url).then(({ data }) => {
    items.value.push(...data.data)
    nextPage.value = data.meta.current_page + 1
    lastPage.value = data.meta.last_page
  })
})
</script>

<template>
  <ul class="grid grid-cols-[repeat(auto-fill,minmax(16rem,1fr))] gap-8">
    <li v-for="item in items" :key="item.id">
      <Link :href="route('items.show', { item: item })">
        <ItemCard :item="item" />
      </Link>
    </li>

    <li v-if="nextPage <= lastPage">
      <ItemCard />
    </li>
  </ul>
  <div ref="listBottomRef" class="h-1"></div>
</template>
