<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import axios from 'axios'

import CommentCard from '@/Components/CommentCard.vue'
import CommentIcon from '@/Components/CommentIcon.vue'
import FavoriteIcon from '@/Components/FavoriteIcon.vue'
import InputError from '@/Components/InputError.vue'
import { toggleFavorite } from '@/functions'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Comment, Item } from '@/types'

const props = defineProps<{
  item: { data: Item }
  comments: { data: Comment[] }
}>()

const form = useForm({
  content: ''
})

function onToggleFavorite() {
  toggleFavorite(props.item.data).then(() => {
    router.reload({ only: ['item'] })
  })
}

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
          <section>
            <h2 class="text-3xl font-bold">{{ item.data.name }}</h2>
            <p class="my-4 text-2xl">¥{{ item.data.price.toLocaleString() }}</p>
            <div class="mx-2 my-4 flex gap-x-8">
              <form @submit.prevent="onToggleFavorite">
                <button
                  type="submit"
                  class="flex w-fit flex-col items-center"
                  :class="{ 'cursor-not-allowed': $page.props.auth.user === null }"
                  :disabled="$page.props.auth.user === null"
                  dusk="favorite-button"
                >
                  <FavoriteIcon
                    class="size-6"
                    :class="{ 'hover:text-gray-500': $page.props.auth.user === null }"
                    :is-favorite="item.data.is_favorite"
                  />
                  <span class="text-xs" dusk="favorite-count">{{ item.data.favorite_count }}</span>
                </button>
              </form>
              <Link
                :href="route('comments.index', { item: item.data })"
                class="flex w-fit flex-col items-center"
              >
                <CommentIcon class="size-6" />
                <span class="text-xs">{{ item.data.comment_count }}</span>
              </Link>
            </div>
          </section>

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
