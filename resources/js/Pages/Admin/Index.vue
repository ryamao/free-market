<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'

import DangerButton from '@/Components/DangerButton.vue'
import UserIcon from '@/Components/UserIcon.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { UserData } from '@/types'

defineProps<{
  users: {
    data: UserData[]
  }
}>()

async function deleteUser(user: UserData) {
  if (!confirm(`ユーザー「${user.name}」を削除します`)) {
    return
  }

  const response = await axios.delete(route('users.destroy', { user: user.id }))
  if (response.status === 204) {
    router.reload()
  } else {
    alert('ユーザーの削除に失敗しました\n' + response.data.message)
  }
}
</script>

<template>
  <Head title="管理ページ" />

  <AdminLayout>
    <section class="m-16">
      <h2 class="my-16 text-center text-2xl font-bold">登録ユーザー一覧</h2>
      <ul class="grid grid-cols-[repeat(auto-fill,minmax(18rem,1fr))] gap-12">
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
              <Link
                :href="route('direct-mails.create', { user: user.id })"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
              >
                メール作成
              </Link>
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
