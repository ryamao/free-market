<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const form = useForm({
  email: '',
  password: ''
})

const submit = () => {
  form.post(route('admin.attempt'), {
    onFinish: () => {
      form.reset('password')
    }
  })
}
</script>

<template>
  <Head title="ログイン" />

  <GuestLayout>
    <section class="mx-auto max-w-lg">
      <h2 class="my-16 text-center text-2xl font-bold">管理者ログイン</h2>

      <form novalidate @submit.prevent="submit">
        <div class="*:h-32">
          <div>
            <InputLabel for="email" value="メールアドレス" />
            <TextInput
              id="email"
              v-model="form.email"
              type="email"
              class="mt-1 block w-full"
              required
              autofocus
              autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div>
            <InputLabel for="password" value="パスワード" />
            <TextInput
              id="password"
              v-model="form.password"
              type="password"
              class="mt-1 block w-full"
              required
              autocomplete="current-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
          </div>
        </div>

        <div class="mt-8">
          <PrimaryButton
            type="submit"
            class="w-full py-1.5"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            ログインする
          </PrimaryButton>
        </div>

        <div class="mt-4 text-center">
          <Link
            :href="route('login')"
            class="rounded-md text-sm text-sky-600 hover:text-sky-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            利用者ログインはこちら
          </Link>
        </div>
      </form>
    </section>
  </GuestLayout>
</template>
