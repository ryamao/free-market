<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import UserIcon from '@/Components/UserIcon.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { UserData } from '@/types'

defineProps<{
  users: {
    data: UserData[]
  }
}>()

function sendEmail(user: UserData) {
  alert(JSON.stringify(user))
}

function deleteUser(user: UserData) {
  alert(JSON.stringify(user))
}
</script>

<template>
  <Head title="管理ページ" />

  <AdminLayout>
    <section class="m-16">
      <h2 class="my-16 text-center text-2xl font-bold">登録ユーザー一覧</h2>
      <ul class="grid grid-cols-[repeat(auto-fill,minmax(16rem,1fr))] gap-12">
        <li v-for="user in users.data" :key="user.id" class="rounded-lg shadow">
          <div class="m-4 space-y-6">
            <div class="flex items-center gap-x-6 p-2">
              <UserIcon
                :user-id="user.id"
                :user-name="user.name"
                :image-url="user.image_url"
                :force-refresh="false"
                class="size-16 text-2xl"
              />
              <span v-if="user.name !== null" class="font-bold">{{ user.name }}</span>
              <span v-else class="font-bold text-gray-300">名称未設定</span>
            </div>
            <div class="flex justify-between">
              <SecondaryButton type="button" class="h-fit" @click.prevent="() => sendEmail(user)">
                メール送信
              </SecondaryButton>
              <DangerButton type="button" class="h-fit" @click.prevent="() => deleteUser(user)">
                削除
              </DangerButton>
            </div>
          </div>
        </li>
      </ul>
    </section>
  </AdminLayout>
</template>
