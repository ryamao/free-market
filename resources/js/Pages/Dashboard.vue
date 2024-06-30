<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

import ItemList from '@/Components/ItemList.vue'
import NavButton from '@/Components/NavButton.vue'
import UserIcon from '@/Components/UserIcon.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'

const tabName = ref<'sales' | 'purchases'>('sales')
</script>

<template>
  <Head title="マイページ" />

  <CommonLayout>
    <section class="m-6">
      <div
        class="mx-auto grid max-w-screen-md grid-cols-[auto,1fr] grid-rows-[auto,auto] items-center gap-6 md:grid-cols-[auto,1fr,auto]"
      >
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
        <Link
          :href="route('profile.edit')"
          class="col-span-2 inline-flex items-center justify-center rounded-md border-2 border-emerald-600 bg-white px-6 py-0.5 text-base font-semibold uppercase tracking-widest text-emerald-600 transition duration-150 ease-in-out hover:bg-emerald-600 hover:text-white focus:bg-emerald-700 focus:text-white active:bg-emerald-800 active:text-white md:col-span-1 md:py-1"
        >
          プロフィールを編集
        </Link>
      </div>
    </section>

    <nav class="border-b border-black pb-1 md:px-24">
      <ul class="flex justify-evenly md:justify-normal md:gap-16">
        <li>
          <NavButton :active="tabName === 'sales'" @click="() => (tabName = 'sales')">
            出品した商品
          </NavButton>
        </li>
        <li>
          <NavButton :active="tabName === 'purchases'" @click="() => (tabName = 'purchases')">
            購入した商品
          </NavButton>
        </li>
      </ul>
    </nav>

    <section class="p-4 md:p-12">
      <ItemList v-if="tabName === 'sales'" :next-url="(page) => route('sales.index', { page })" />
      <ItemList
        v-else-if="tabName === 'purchases'"
        :next-url="(page) => route('purchases.index', { page })"
      />
    </section>
  </CommonLayout>
</template>
