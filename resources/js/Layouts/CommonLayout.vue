<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'

import TextInput from '@/Components/TextInput.vue'

import LogoUrl from '../../img/logo.svg?url'

const props = defineProps<{
  searchString?: string
}>()

const form = useForm({
  q: props.searchString ?? ''
})

function search() {
  form.get(route('search-results'))
}
</script>

<template>
  <header class="bg-black px-4 py-2">
    <nav class="grid grid-cols-[auto_5fr_6fr] items-center justify-between gap-x-6">
      <h1>
        <Link href="/">
          <img :src="LogoUrl" alt="COACHTECH" class="h-6" />
        </Link>
      </h1>

      <form @submit.prevent="search">
        <TextInput
          v-model="form.q"
          type="search"
          placeholder="なにをお探しですか？"
          class="h-10 w-full"
        />
      </form>

      <div class="flex justify-end">
        <ul class="flex w-full max-w-72 items-center justify-between">
          <template v-if="$page.props.auth.user">
            <li>
              <Link :href="route('logout')" class="text-white" method="post">ログアウト</Link>
            </li>
            <li>
              <Link :href="route('dashboard')" class="text-white">マイページ</Link>
            </li>
            <li>
              <Link href="#" class="rounded-sm bg-white px-4 py-1.5 text-black">出品</Link>
            </li>
          </template>
          <template v-else>
            <li>
              <Link :href="route('login')" class="text-white">ログイン</Link>
            </li>
            <li>
              <Link :href="route('register')" class="text-white">会員登録</Link>
            </li>
          </template>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <slot />
  </main>
</template>
