<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

import ItemList from '@/Components/ItemList.vue'
import NavLink from '@/Components/NavLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item, Paginator } from '@/types'

const props = defineProps<{
  routeName: string
  items: Paginator<Item>
  searchString?: string
}>()

function makeNextUrl(nextPage: number): string {
  return route(props.routeName, {
    page: nextPage,
    q: props.searchString
  })
}
</script>

<template>
  <Head title="商品一覧" />

  <CommonLayout :search-string="searchString">
    <nav class="border-b border-black px-24 pb-1 pt-8">
      <ul class="flex gap-16">
        <li>
          <NavLink :href="route('items.index')" :active="routeName === 'items.index'">
            新着商品
          </NavLink>
        </li>
        <li v-if="routeName === 'items.search'">
          <NavLink :href="route('items.search', { q: searchString })" :active="true">
            検索結果
          </NavLink>
        </li>
        <li v-if="$page.props.auth.user">
          <NavLink :href="route('mylist.index')" :active="routeName === 'mylist.index'">
            マイリスト
          </NavLink>
        </li>
      </ul>
    </nav>

    <div class="p-12">
      <ItemList :items="items" :next-url="makeNextUrl" />
    </div>
  </CommonLayout>
</template>
