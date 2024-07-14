<script setup lang="ts">
import { Link, router, useForm } from '@inertiajs/vue3'
import axios from 'axios'

import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import TextInput from '@/Components/TextInput.vue'

import IconUrl from '../../img/icon.svg?url'
import LogoUrl from '../../img/logo.svg?url'

const props = defineProps<{
  searchString?: string
}>()

const form = useForm({
  q: props.searchString ?? ''
})

function search() {
  form.get(route('home.search'))
}

function handleAdminLogout() {
  axios.post(route('admin.logout')).then(() => {
    router.visit(route('admin.login'))
  })
}

function handleUserLogout() {
  axios.post(route('logout')).then(() => {
    router.visit(route('login'))
  })
}
</script>

<template>
  <header class="bg-black px-4">
    <nav class="grid h-14 grid-cols-6 items-center justify-between md:grid-cols-3">
      <h1>
        <Link href="/">
          <img :src="IconUrl" alt="COACHTECH" class="inline h-6 md:hidden" />
          <img :src="LogoUrl" alt="COACHTECH" class="hidden h-6 md:inline" />
        </Link>
      </h1>

      <form class="col-span-4 md:col-span-1 md:mr-4" @submit.prevent="search">
        <TextInput
          v-model="form.q"
          type="search"
          placeholder="なにをお探しですか？"
          class="h-10 w-full"
        />
      </form>

      <div class="hidden justify-end md:flex">
        <template v-if="$page.props.auth.admin">
          <ul class="flex w-full max-w-64 items-center justify-evenly">
            <li>
              <button type="button" class="text-white" @click.prevent="handleAdminLogout">
                ログアウト
              </button>
            </li>
            <li>
              <Link :href="route('admin.index')" class="text-white">管理ページ</Link>
            </li>
          </ul>
        </template>
        <template v-else-if="$page.props.auth.user">
          <ul class="flex w-full max-w-72 items-center justify-between">
            <li>
              <button type="button" class="text-white" @click.prevent="handleUserLogout">
                ログアウト
              </button>
            </li>
            <li>
              <Link :href="route('dashboard')" class="text-white">マイページ</Link>
            </li>
            <li>
              <Link
                :href="route('sales.create')"
                class="rounded-sm bg-white px-4 py-1.5 text-black"
              >
                出品
              </Link>
            </li>
          </ul>
        </template>
        <template v-else>
          <ul class="flex w-full max-w-64 items-center justify-evenly">
            <li>
              <Link :href="route('login')" class="text-white">ログイン</Link>
            </li>
            <li>
              <Link :href="route('register')" class="text-white">会員登録</Link>
            </li>
          </ul>
        </template>
      </div>

      <div class="flex items-center justify-end md:hidden">
        <!-- Settings Dropdown -->
        <div class="relative ms-3">
          <Dropdown align="right" width="48">
            <template #trigger>
              <span class="inline-flex rounded-md">
                <div
                  class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-xl font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                >
                  ≡
                </div>
              </span>
            </template>

            <template #content>
              <template v-if="$page.props.auth.admin">
                <DropdownLink :href="route('admin.index')">管理ページ</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">
                  ログアウト
                </DropdownLink>
              </template>
              <template v-else-if="$page.props.auth.user">
                <DropdownLink :href="route('dashboard')">マイページ</DropdownLink>
                <DropdownLink :href="route('sales.create')">出品</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">
                  ログアウト
                </DropdownLink>
              </template>
              <template v-else>
                <DropdownLink :href="route('login')">ログイン</DropdownLink>
                <DropdownLink :href="route('register')">会員登録</DropdownLink>
              </template>
            </template>
          </Dropdown>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <slot />
  </main>
</template>
