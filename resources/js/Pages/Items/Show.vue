<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import ItemDetailTop from '@/Components/ItemDetailTop.vue'
import PurchaseLink from '@/Components/PurchaseLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item } from '@/types'

const props = defineProps<{
  item: {
    data: Item
  }
}>()
</script>

<template>
  <Head :title="item.data.name" />

  <CommonLayout>
    <div class="mx-8 mb-8 mt-16">
      <div class="mx-auto grid max-w-4xl grid-cols-2 gap-x-16">
        <div>
          <img :src="item.data.image_url" :alt="item.data.name" class="w-full object-contain" />
        </div>

        <div class="space-y-10">
          <div class="space-y-5">
            <ItemDetailTop :item="item.data" :is-authenticated="$page.props.auth.user !== null" />
            <PurchaseLink :item="item.data" :is-authenticated="$page.props.auth.user !== null" />
          </div>

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
