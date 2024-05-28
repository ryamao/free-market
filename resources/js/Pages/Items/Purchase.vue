<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'

import PrimaryButton from '@/Components/PrimaryButton.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item } from '@/types'

defineProps<{
  item: {
    data: Item
  }
}>()

const page = usePage()
const postcode = page.props.auth.user.postcode
const postcodeString = postcode.slice(0, 3) + '-' + postcode.slice(3)
const address = page.props.auth.user.address
const building = page.props.auth.user.building
</script>

<template>
  <Head :title="item.data.name" />

  <CommonLayout>
    <div class="mx-auto my-16 grid max-w-screen-md grid-cols-[1fr,auto] gap-x-8">
      <div class="space-y-8">
        <div class="flex gap-x-8">
          <div>
            <img :src="item.data.image_url" alt="" class="h-32" />
          </div>
          <div class="flex flex-col justify-evenly">
            <h2 class="text-2xl font-bold">{{ item.data.name }}</h2>
            <p class="text-xl">¥{{ item.data.price.toLocaleString() }}</p>
          </div>
        </div>
        <div>
          <h3 class="text-lg font-bold">支払い方法</h3>
        </div>
        <div class="grid grid-cols-[auto,1fr] gap-x-8 gap-y-4">
          <h3 class="col-span-2 text-lg font-bold">配送先</h3>
          <h4>郵便番号</h4>
          <div>{{ postcodeString }}</div>
          <h4>住所</h4>
          <div>{{ address }}</div>
          <h4>建物名</h4>
          <div>{{ building }}</div>
        </div>
      </div>

      <div class="space-y-8">
        <div class="grid grid-cols-2 gap-8 border border-gray-500 p-8">
          <div>商品代金</div>
          <div>¥{{ item.data.price.toLocaleString() }}</div>
          <div>支払い金額</div>
          <div>¥{{ item.data.price.toLocaleString() }}</div>
          <div>支払い方法</div>
          <div>コンビニ払い</div>
        </div>
        <div>
          <PrimaryButton class="w-full py-0.5">購入する</PrimaryButton>
        </div>
      </div>
    </div>
  </CommonLayout>
</template>
