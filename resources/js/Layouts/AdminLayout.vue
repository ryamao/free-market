<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'

import LogoUrl from '../../img/logo.svg?url'

function handleLogout() {
  axios.post(route('admin.logout')).then(() => {
    router.visit(route('admin.login'))
  })
}
</script>

<template>
  <header class="bg-black p-4">
    <nav class="grid grid-cols-[auto,1fr] items-center justify-between">
      <h1>
        <Link href="/">
          <img :src="LogoUrl" alt="COACHTECH" class="h-6" />
        </Link>
      </h1>

      <div class="flex justify-end">
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
    </nav>
  </header>

  <main>
    <slot />
  </main>
</template>
