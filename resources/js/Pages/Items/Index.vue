<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { useIntersectionObserver } from '@vueuse/core'
import axios from 'axios'
import { ref } from 'vue'

import NavLink from '@/Components/NavLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item, Paginator } from '@/types'

const props = defineProps<{
  items: Paginator<Item>
  searchString?: string
}>()

const allItems = ref<Item[]>(props.items.data)
const currentPage = ref<number>(props.items.meta.current_page)
const lastPage = ref<number>(props.items.meta.last_page)
const totalItems = ref<number>(props.items.meta.total)
const pageBottom = ref<HTMLElement | null>(null)

useIntersectionObserver(pageBottom, ([{ isIntersecting }]) => {
  if (!isIntersecting || currentPage.value >= lastPage.value) {
    return
  }

  axios.get(route('items.index', { page: currentPage.value + 1 })).then(({ data }) => {
    allItems.value = [...allItems.value, ...data.data]
    currentPage.value = data.meta.current_page
    lastPage.value = data.meta.last_page
  })
})
</script>

<template>
  <Head title="商品一覧" />

  <CommonLayout :search-string="searchString">
    <nav class="border-b border-black px-24 pb-1 pt-8">
      <ul class="flex gap-16">
        <li>
          <NavLink :href="route('items.index')" :active="$page.url === '/'">新着商品</NavLink>
        </li>
        <li v-if="$page.url.startsWith('/search')">
          <NavLink :href="route('items.search', { q: searchString })" :active="true">
            検索結果
          </NavLink>
        </li>
        <li v-if="$page.props.auth.user">
          <NavLink :href="route('items.mylist')" :active="$page.url === '/mylist'">
            マイリスト
          </NavLink>
        </li>
      </ul>
    </nav>

    <div class="p-12">
      <ul dusk="item-list" class="grid grid-cols-[repeat(auto-fill,minmax(8rem,1fr))] gap-16">
        <li v-for="item in allItems" :key="item.id" class="flex justify-center">
          <Link :href="route('items.show', { item: item })">
            <img :src="item.image_url" alt="商品画像" class="size-32" />
          </Link>
        </li>

        <template v-if="currentPage < lastPage">
          <li ref="pageBottom" class="flex justify-center">
            <div class="size-32 animate-pulse bg-gray-200"></div>
          </li>

          <li
            v-for="i in Array.from({ length: totalItems - allItems.length - 1 }).map((_, i) => i)"
            :key="i"
            class="flex justify-center"
          >
            <div class="size-32 animate-pulse bg-gray-200"></div>
          </li>
        </template>
      </ul>
    </div>
  </CommonLayout>
</template>
