<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

import ItemList from '@/Components/ItemList.vue'
import NavLink from '@/Components/NavLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'

const props = defineProps<{
  routeName: 'home.index' | 'home.search' | 'home.mylist'
  searchString?: string
}>()

function nextUrl(page: number): string {
  switch (props.routeName) {
    case 'home.index':
      return route('items.index', { page })
    case 'home.search':
      return route('items.index', { page, q: props.searchString })
    case 'home.mylist':
      return route('favorites.index', { page })
  }
}
</script>

<template>
  <Head title="商品一覧" />

  <CommonLayout :search-string="searchString">
    <nav class="border-b border-black pb-1 pt-2 md:px-24 md:pt-8">
      <ul class="flex justify-evenly md:justify-normal md:gap-16">
        <li>
          <NavLink :href="route('home.index')" :active="routeName === 'home.index'">
            新着商品
          </NavLink>
        </li>
        <li v-if="routeName === 'home.search'">
          <NavLink :href="route('home.search', { q: searchString })" :active="true">
            検索結果
          </NavLink>
        </li>
        <li v-if="$page.props.auth.user">
          <NavLink :href="route('home.mylist')" :active="routeName === 'home.mylist'">
            マイリスト
          </NavLink>
        </li>
      </ul>
    </nav>

    <div class="p-4 md:p-12">
      <ItemList :next-url="nextUrl" />
    </div>
  </CommonLayout>
</template>
