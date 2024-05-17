<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item, Paginator } from '@/types'

defineProps<{
  items: Paginator<Item>
  searchString?: string
}>()
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
        <li v-for="item in items.data" :key="item.id" class="flex justify-center">
          <Link :href="route('items.show', { item: item })">
            <img :src="item.image_url" alt="商品画像" class="size-32" />
          </Link>
        </li>
      </ul>
    </div>
  </CommonLayout>
</template>
