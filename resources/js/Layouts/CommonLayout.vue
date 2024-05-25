<script setup lang="ts">
import { Link, router, useForm } from '@inertiajs/vue3'
import axios from 'axios'

import TextInput from '@/Components/TextInput.vue'

import LogoUrl from '../../img/logo.svg?url'

const props = defineProps<{
  searchString?: string
}>()

const form = useForm({
  q: props.searchString ?? ''
})

function search() {
  form.get(route('items.search'))
}

function handleLogout() {
  axios.post(route('logout')).then(() => {
    router.visit(route('login'))
  })
}
</script>

<template>
  <header class="bg-black px-4 py-2">
    <nav class="grid grid-cols-3 items-center justify-between">
      <h1>
        <Link href="/">
          <img :src="LogoUrl" alt="COACHTECH" class="h-6" />
        </Link>
      </h1>

      <form class="px-4" @submit.prevent="search">
        <TextInput
          v-model="form.q"
          type="search"
          placeholder="なにをお探しですか？"
          class="h-10 w-full"
        />
      </form>

      <div class="flex justify-end">
        <template v-if="$page.props.auth.user">
          <ul class="flex w-full max-w-72 items-center justify-between">
            <li>
              <button type="button" class="text-white" @click.prevent="handleLogout">
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
    </nav>
  </header>

  <main>
    <slot />
  </main>
</template>
