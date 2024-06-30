<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'

import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

import IconUrl from '../../img/icon.svg?url'
import LogoUrl from '../../img/logo.svg?url'

function handleLogout() {
  axios.post(route('admin.logout')).then(() => {
    router.visit(route('admin.login'))
  })
}
</script>

<template>
  <header class="bg-black px-4 py-2.5">
    <nav class="grid grid-cols-[auto,1fr] items-center justify-between">
      <h1>
        <Link href="/">
          <img :src="IconUrl" alt="COACHTECH" class="inline h-6 md:hidden" />
          <img :src="LogoUrl" alt="COACHTECH" class="hidden h-6 md:inline" />
        </Link>
      </h1>

      <div class="hidden justify-end md:flex">
        <ul class="flex w-full max-w-64 items-center justify-evenly">
          <li>
            <button type="button" class="text-white" @click.prevent="handleLogout">
              ログアウト
            </button>
          </li>
          <li>
            <Link :href="route('admin.index')" class="text-white">管理ページ</Link>
          </li>
        </ul>
      </div>

      <div class="flex items-center justify-end md:hidden">
        <!-- Settings Dropdown -->
        <div class="relative ms-3">
          <Dropdown align="right" width="48">
            <template #trigger>
              <div class="rounded-md">
                <div
                  class="inline-flex h-full items-center rounded-md border border-transparent bg-white px-3 py-2 text-xl font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                >
                  ≡
                </div>
              </div>
            </template>

            <template #content>
              <DropdownLink :href="route('admin.index')">管理ページ</DropdownLink>
              <DropdownLink :href="route('logout')" method="post" as="button">
                ログアウト
              </DropdownLink>
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
