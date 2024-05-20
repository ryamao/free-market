<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'

import CommentIcon from '@/Components/CommentIcon.vue'
import FavoriteIcon from '@/Components/FavoriteIcon.vue'
import { toggleFavorite } from '@/functions'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item } from '@/types'

const props = defineProps<{
  item: {
    data: Item
  }
}>()

function onToggleFavorite() {
  toggleFavorite(props.item.data).then(() => {
    router.reload({ only: ['item'] })
  })
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

        <div class="space-y-10">
          <section>
            <h2 class="text-3xl font-bold">{{ item.data.name }}</h2>
            <p class="my-4 text-2xl">¥{{ item.data.price.toLocaleString() }}</p>
            <div class="mx-2 my-4 flex gap-x-8">
              <form @submit.prevent="onToggleFavorite">
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
                <span class="text-xs">{{ item.data.comment_count }}</span>
              </Link>
            </div>
            <div class="my-4">
              <Link
                v-if="$page.props.auth.user !== null"
                :href="route('purchase.create', { item: item.data })"
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
            </div>
          </section>

          <section>
            <h3 class="mb-2 text-xl font-bold">商品説明</h3>
            <p class="break-words">
              <template v-for="(line, index) in item.data.description.split(/\n/)" :key="index">
                <span>{{ line }}</span>
                <br />
              </template>
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
