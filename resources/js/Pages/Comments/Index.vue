<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3'
import axios from 'axios'

import CommentCard from '@/Components/CommentCard.vue'
import InputError from '@/Components/InputError.vue'
import ItemDetailTop from '@/Components/ItemDetailTop.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Comment, Item } from '@/types'

const props = defineProps<{
  item: { data: Item }
  comments: { data: Comment[] }
}>()

const form = useForm({
  content: ''
})

function onSendComment() {
  axios
    .post(route('comments.store', { item: props.item.data }), form.data())
    .then(() => {
      form.reset()
      router.reload()
    })
    .catch((error) => {
      if (error.response.status === 422) {
        form.setError('content', error.response.data.message)
      }
    })
}
</script>

<template>
  <Head title="コメント" />

  <CommonLayout>
    <div class="mx-8 mb-8 mt-16">
      <div class="mx-auto grid max-w-4xl grid-cols-2 gap-x-16">
        <div>
          <img :src="item.data.image_url" :alt="item.data.name" class="w-full object-contain" />
        </div>

        <div class="space-y-10">
          <ItemDetailTop :item="item.data" :is-authenticated="$page.props.auth.user !== null" />

          <section>
            <ul class="space-y-5">
              <li v-for="comment in comments.data" :key="comment.id">
                <CommentCard
                  :comment="comment"
                  :is-seller="comment.user.id === item.data.seller.id"
                />
              </li>
            </ul>
          </section>

          <section>
            <form @submit.prevent="onSendComment">
              <InputError :message="form.errors.content" />
              <textarea
                v-model="form.content"
                class="h-24 w-full rounded-lg border border-gray-300 p-2"
                :class="{
                  'cursor-not-allowed bg-gray-100':
                    $page.props.auth.user === null || form.processing
                }"
                :disabled="$page.props.auth.user === null || form.processing"
              />
              <div class="flex justify-end">
                <button
                  type="submit"
                  class="inline-flex w-full items-center justify-center rounded-md border border-transparent px-4 py-0.5 text-lg font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out"
                  :class="{
                    'opacity-25': form.processing,
                    'cursor-not-allowed bg-gray-300': $page.props.auth.user === null,
                    'bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800':
                      $page.props.auth.user !== null
                  }"
                  :disabled="$page.props.auth.user === null || form.processing"
                >
                  コメントを送信する
                </button>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </CommonLayout>
</template>
