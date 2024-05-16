<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item, User } from '@/types'

const page = usePage()

const searchString = page.url.startsWith('/search?q=') ? page.url.split('=')[1] : ''

const user1: User = {
  id: 1,
  name: 'user1',
  email: 'test@example.com',
  email_verified_at: '2021-10-01T00:00:00Z'
}

const items: Item[] = Array.from({ length: 30 }, (_, i) => ({
  id: i + 1,
  user: user1,
  condition: '良品',
  name: `商品${i + 1}`,
  price: 1000,
  image_url: 'https://via.placeholder.com/150?text=Item' + (i + 1),
  description: `商品${i + 1}の説明`
}))
</script>

<template>
  <Head title="トップページ" />

  <CommonLayout :search-string="searchString">
    <nav class="border-b border-black px-24 pb-1 pt-8">
      <ul class="flex gap-16">
        <li>
          <NavLink :href="route('latest-items')" :active="$page.url === '/'">新着商品</NavLink>
        </li>
        <li v-if="$page.url.startsWith('/search')">
          <NavLink :href="route('search-results')" :active="true">検索結果</NavLink>
        </li>
        <li v-if="$page.props.auth.user">
          <NavLink :href="route('wish-list')" :active="$page.url === '/mylist'">マイリスト</NavLink>
        </li>
      </ul>
    </nav>

    <section class="p-12">
      <ul class="grid grid-cols-[repeat(auto-fill,minmax(8rem,1fr))] gap-16">
        <li v-for="item in items" :key="item.id" class="flex justify-center">
          <Link href="#">
            <img :src="item.image_url" alt="商品画像" class="size-32" />
          </Link>
        </li>
      </ul>
    </section>
  </CommonLayout>
</template>
