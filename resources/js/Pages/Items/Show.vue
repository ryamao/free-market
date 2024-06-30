<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { loadStripe } from '@stripe/stripe-js'

import ItemDetailTop from '@/Components/ItemDetailTop.vue'
import PurchaseLink from '@/Components/PurchaseLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item } from '@/types'

const props = defineProps<{
  item: {
    data: Item
  }
  payment: {
    status: string
    clientSecret: string
  } | null
}>()

async function handleNextAction() {
  if (props.payment?.status !== 'requires_action') {
    return
  }

  const stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
  if (!stripe) {
    return
  }

  await stripe.handleNextAction({ clientSecret: props.payment.clientSecret })
}
</script>

<template>
  <Head :title="item.data.name" />

  <CommonLayout>
    <div class="m-4 md:mx-8 md:mt-16">
      <div class="mx-auto max-w-4xl sm:grid sm:grid-cols-2 sm:gap-x-8 md:gap-x-16">
        <div class="mx-auto max-w-96 sm:w-full">
          <img :src="item.data.image_url" :alt="item.data.name" class="w-full object-contain" />
        </div>

        <div class="mt-10 space-y-10 sm:mt-0">
          <div class="space-y-5">
            <ItemDetailTop :item="item.data" :is-authenticated="$page.props.auth.user !== null" />
            <template v-if="item.data.seller && item.data.seller.id !== $page.props.auth.user?.id">
              <PurchaseLink
                v-if="!item.data.is_sold"
                :item="item.data"
                :is-authenticated="$page.props.auth.user !== null"
              />
              <button
                v-else-if="payment?.status === 'requires_action'"
                type="button"
                class="inline-flex w-full items-center justify-center rounded-md border-2 border-emerald-600 bg-white py-0.5 text-lg font-semibold uppercase tracking-widest text-emerald-600 transition duration-150 ease-in-out hover:bg-emerald-600 hover:text-white focus:bg-emerald-700 focus:text-white active:bg-emerald-800 active:text-white"
                @click.prevent="handleNextAction"
              >
                支払いを完了する
              </button>
            </template>
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
            <ul class="flex flex-wrap gap-x-4 gap-y-2">
              <li v-for="category in props.item.data.categories" :key="category">
                <Link
                  :href="route('home.search', { q: 'category:' + category })"
                  class="rounded-full bg-gray-200 px-4 py-0.5 text-sm transition duration-150 ease-in-out hover:bg-gray-300"
                >
                  {{ category }}
                </Link>
              </li>
            </ul>
            <h4 class="text-base font-bold">商品の状態</h4>
            <div>
              <Link
                :href="route('home.search', { q: 'condition:' + props.item.data.condition })"
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
