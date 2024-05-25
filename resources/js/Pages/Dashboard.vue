<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { useIntersectionObserver } from '@vueuse/core'
import axios from 'axios'
import { ref } from 'vue'

import ItemCard from '@/Components/ItemCard.vue'
import NavLink from '@/Components/NavLink.vue'
import UserIcon from '@/Components/UserIcon.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item } from '@/types'

const props = defineProps<{
  routeName: 'dashboard' | 'dashboard.purchases'
}>()

const items = ref<Item[]>([])
const nextPage = ref<number>(1)
const lastPage = ref<number>(1)

const lastItemRef = ref<HTMLElement | null>(null)

useIntersectionObserver(lastItemRef, ([{ isIntersecting }]) => {
  if (!isIntersecting || nextPage.value > lastPage.value) {
    return
  }

  let endpoint
  switch (props.routeName) {
    case 'dashboard':
      endpoint = 'sales.index'
      break
    case 'dashboard.purchases':
      endpoint = 'purchases.index'
      break
  }

  axios.get(route(endpoint, { page: nextPage.value })).then(({ data }) => {
    items.value.push(...data.data)
    nextPage.value = data.meta.current_page + 1
    lastPage.value = data.meta.last_page
  })
})
</script>

<template>
  <Head title="マイページ" />

  <CommonLayout>
    <section class="mx-auto my-12 flex max-w-screen-md items-center justify-between px-8">
      <div class="flex items-center gap-x-8">
        <UserIcon
          :user-id="$page.props.auth.user.id"
          :user-name="$page.props.auth.user.name"
          :image-url="$page.props.auth.user.image_url"
          :force-refresh="true"
          class="size-32 text-5xl"
        />
        <h2
          class="text-2xl font-bold"
          :class="{ 'text-gray-300': $page.props.auth.user.name == undefined }"
        >
          {{ $page.props.auth.user.name ?? '(名前未設定)' }}
        </h2>
      </div>
      <div>
        <Link
          :href="route('profile.edit')"
          class="inline-flex items-center justify-center rounded-md border-2 border-emerald-600 bg-white px-6 py-1 text-base font-semibold uppercase tracking-widest text-emerald-600 transition duration-150 ease-in-out hover:bg-emerald-600 hover:text-white focus:bg-emerald-700 focus:text-white active:bg-emerald-800 active:text-white"
        >
          プロフィールを編集
        </Link>
      </div>
    </section>

    <nav class="border-b border-black px-24 pb-1">
      <ul class="flex gap-16">
        <li>
          <NavLink :href="route('dashboard')" :active="routeName === 'dashboard'">
            出品した商品
          </NavLink>
        </li>
        <li>
          <NavLink
            :href="route('dashboard.purchases')"
            :active="routeName === 'dashboard.purchases'"
          >
            購入した商品
          </NavLink>
        </li>
      </ul>
    </nav>

    <section class="p-12">
      <ul class="grid grid-cols-[repeat(auto-fill,minmax(16rem,1fr))] gap-8">
        <li v-for="item in items" :key="item.id">
          <Link :href="route('items.show', { item: item })">
            <ItemCard :item="item" />
          </Link>
        </li>

        <li v-if="nextPage <= lastPage" ref="lastItemRef">
          <ItemCard />
        </li>
      </ul>
    </section>
  </CommonLayout>
</template>
