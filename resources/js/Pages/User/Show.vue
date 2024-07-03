<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

import ItemList from '@/Components/ItemList.vue'
import NavButton from '@/Components/NavButton.vue'
import UserIcon from '@/Components/UserIcon.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { UserData } from '@/types'

defineProps<{
  user: {
    data: UserData
  }
}>()

const tabName = ref<'sales' | 'purchases'>('sales')
</script>

<template>
  <Head :title="user.data.name ?? '名前未設定'" />

  <CommonLayout>
    <section class="m-6">
      <div
        class="mx-auto grid max-w-screen-md grid-cols-[auto,1fr] grid-rows-[auto,auto] items-center gap-6 md:grid-cols-[auto,1fr,auto]"
      >
        <UserIcon
          :user-id="user.data.id"
          :user-name="user.data.name"
          :image-url="user.data.image_url"
          :force-refresh="true"
          class="size-32 text-5xl"
        />
        <h2 class="text-2xl font-bold" :class="{ 'text-gray-300': user.data.name == undefined }">
          {{ user.data.name ?? '(名前未設定)' }}
        </h2>
      </div>
    </section>

    <nav class="border-b border-black pb-1 md:px-24">
      <ul class="flex justify-evenly md:justify-normal md:gap-16">
        <li>
          <NavButton :active="tabName === 'sales'" @click="() => (tabName = 'sales')">
            出品した商品
          </NavButton>
        </li>
      </ul>
    </nav>

    <section class="p-4 md:p-12">
      <ItemList
        v-if="tabName === 'sales'"
        :next-url="(page) => route('users.sales', { user: user.data.id, page })"
      />
    </section>
  </CommonLayout>
</template>
