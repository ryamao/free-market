<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'

import CommentIcon from '@/Components/CommentIcon.vue'
import FavoriteIcon from '@/Components/FavoriteIcon.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item } from '@/types'

const props = defineProps<{
  item: {
    data: Item
  }
}>()

function addToFavorite() {
  axios.post(route('mylist.store', { item: props.item.data })).then(() => {
    router.reload({ only: ['item'] })
  })
}

function toggleFavorite() {
  if (props.item.data.is_favorite) {
    // TODO お気に入りから削除
  } else {
    addToFavorite()
  }
}
</script>

<template>
  <Head :title="item.data.name" />

  <CommonLayout>
    <div class="mx-8 mt-16">
      <div class="mx-auto grid max-w-4xl grid-cols-2">
        <div class="pr-16">
          <img :src="item.data.image_url" :alt="item.data.name" class="w-full object-contain" />
        </div>

        <div class="space-y-8">
          <section>
            <h2 class="text-3xl font-bold">{{ item.data.name }}</h2>
            <p class="my-4 text-2xl">¥{{ item.data.price.toLocaleString() }}</p>
            <div class="mx-2 my-4 flex gap-x-8">
              <form @submit.prevent="toggleFavorite">
                <button
                  type="submit"
                  class="flex w-fit flex-col items-center"
                  :class="{ 'cursor-not-allowed': $page.props.auth.user === null }"
                  :disabled="$page.props.auth.user === null"
                  dusk="favorite-button"
                >
                  <FavoriteIcon
                    class="size-6"
                    :class="{ 'hover:text-gray-500': $page.props.auth.user === null }"
                    :is-favorite="item.data.is_favorite"
                  />
                  <span class="text-xs" dusk="favorite-count">{{ item.data.favorite_count }}</span>
                </button>
              </form>
              <Link
                :href="route('comments.index', { item: item.data })"
                class="flex w-fit flex-col items-center"
              >
                <CommentIcon class="size-6" />
                <span class="text-xs">0</span>
              </Link>
            </div>
            <div class="my-4">
              <Link
                :href="route('purchase.create', { item: item.data })"
                class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-emerald-600 px-4 py-0.5 text-lg font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out"
                :class="{
                  'cursor-not-allowed bg-gray-300': $page.props.auth.user === null,
                  'hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800':
                    $page.props.auth.user !== null
                }"
                :disabled="$page.props.auth.user === null"
              >
                購入する
              </Link>
            </div>
          </section>

          <section>
            <h3 class="mb-2 text-xl font-bold">商品説明</h3>
            <p v-for="(line, index) in item.data.description.split('\n')" :key="index">
              {{ line }}
            </p>
          </section>

          <section class="grid grid-cols-[auto,1fr] gap-x-8 gap-y-4">
            <h3 class="col-span-2 text-xl font-bold">商品の情報</h3>
            <h4 class="text-base font-bold">カテゴリー</h4>
            <ul class="flex gap-x-4">
              <li v-for="category in props.item.data.categories" :key="category">
                <Link
                  :href="route('items.search', { q: 'category:' + category })"
                  class="rounded-full bg-gray-200 px-4 py-0.5 text-sm transition duration-150 ease-in-out hover:bg-gray-300"
                >
                  {{ category }}
                </Link>
              </li>
            </ul>
            <h4 class="text-base font-bold">商品の状態</h4>
            <div>
              <Link
                :href="route('items.search', { q: 'condition:' + props.item.data.condition })"
                class="rounded-full bg-gray-200 px-4 py-0.5 text-sm transition duration-150 ease-in-out hover:bg-gray-300"
              >
                {{ props.item.data.condition }}
              </Link>
            </div>
          </section>
        </div>
      </div>
    </div>
  </CommonLayout>
</template>