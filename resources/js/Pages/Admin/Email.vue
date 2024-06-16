<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'

import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { UserData } from '@/types'

defineProps<{
  user: {
    data: UserData
  }
}>()

const form = useForm({
  title: '',
  content: ''
})

function sendDirectMail() {
  alert(JSON.stringify(form))
}
</script>

<template>
  <Head title="ダイレクトメール" />

  <AdminLayout>
    <section class="mx-auto my-16 max-w-screen-sm">
      <form novalidate class="space-y-8" @submit.prevent="sendDirectMail">
        <div>
          <InputLabel for="name" value="宛名" />
          <input
            id="name"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-500 text-gray-500"
            :value="(user.data.name ?? '匿名ユーザー') + ' 様'"
            disabled
          />
        </div>

        <div>
          <InputLabel for="title" value="件名" />
          <TextInput
            id="title"
            v-model="form.title"
            type="text"
            class="mt-1 block w-full"
            required
            autofocus
          />
          <InputError class="mt-2" :message="form.errors.title" />
        </div>

        <div>
          <InputLabel for="content" value="本文" />
          <textarea
            v-model="form.content"
            class="w-full rounded-md border-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
            rows="10"
          />
          <InputError class="mt-2" :message="form.errors.content" />
        </div>

        <PrimaryButton type="submit" class="w-full py-1.5">メールを送信する</PrimaryButton>
      </form>
    </section>
  </AdminLayout>
</template>
