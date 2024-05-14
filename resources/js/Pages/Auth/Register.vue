<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'

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
  alert(JSON.stringify(form))
  form.reset('password')
}
</script>

<template>
  <GuestLayout>
    <section class="mx-auto mt-16 max-w-lg">
      <h2 class="text-center text-2xl font-bold">会員登録</h2>

      <form novalidate @submit.prevent="submit">
        <div class="mt-14">
          <InputLabel for="email" value="メールアドレス" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            required
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-12">
          <InputLabel for="password" value="パスワード" />
          <TextInput
            id="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-20">
          <PrimaryButton
            type="submit"
            class="w-full"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            登録する
          </PrimaryButton>
        </div>

        <div class="mt-4 text-center">
          <Link
            :href="route('login')"
            class="rounded-md text-sm text-sky-600 hover:text-sky-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            ログインはこちら
          </Link>
        </div>
      </form>
    </section>
  </GuestLayout>
</template>
