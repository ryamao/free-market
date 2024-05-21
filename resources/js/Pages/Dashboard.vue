<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { UserData } from '@/types'

defineProps<{
  user: UserData
  routeName: string
}>()
</script>

<template>
  <Head title="マイページ" />

  <CommonLayout>
    <section class="mx-auto my-12 flex max-w-screen-md items-center justify-between px-8">
      <div class="flex items-center gap-x-8">
        <span>
          <img v-if="user.image_url" :src="user.image_url" alt="" class="size-20 rounded-full" />
          <div
            v-else
            class="flex size-20 items-center justify-center rounded-full text-3xl text-white after:content-[attr(data-text)]"
            :class="'bg-blue-500'"
            :data-text="user.name?.[0]"
          ></div>
        </span>
        <h2 class="text-2xl font-bold" :class="{ 'text-gray-300': user.name === null }">
          {{ user.name ?? '(名前未設定)' }}
        </h2>
      </div>
      <div>
        <Link
          :href="route('profile.edit')"
          class="inline-flex items-center justify-center rounded-md border border-emerald-600 bg-white px-6 py-1 text-base font-semibold uppercase tracking-widest text-emerald-600 transition duration-150 ease-in-out hover:bg-emerald-600 hover:text-white focus:bg-emerald-700 focus:text-white active:bg-emerald-800 active:text-white"
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
          <NavLink :href="route('purchase.index')" :active="routeName === 'purchase.index'">
            購入した商品
          </NavLink>
        </li>
      </ul>
    </nav>

    <section class="p-12">
      <!-- <ItemList :items="items" :next-url="makeNextUrl" /> -->
    </section>
  </CommonLayout>
</template>
