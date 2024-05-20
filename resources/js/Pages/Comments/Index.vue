<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'

import CommentIcon from '@/Components/CommentIcon.vue'
import FavoriteIcon from '@/Components/FavoriteIcon.vue'
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
  alert('TODO コメント送信')
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
                <div
                  class="flex items-center"
                  :class="{ 'flex-row-reverse': comment.user.id === item.data.seller.id }"
                >
                  <Link
                    href="#"
                    class="flex gap-x-2 hover:opacity-75 focus:opacity-75"
                    :class="{ 'flex-row-reverse': comment.user.id === item.data.seller.id }"
                  >
                    <img
                      v-if="comment.user.image_url"
                      :src="comment.user.image_url"
                      alt=""
                      class="size-6 rounded-full"
                    />
                    <div
                      v-else
                      class="flex size-6 items-center justify-center rounded-full bg-red-500 text-sm text-white after:content-[attr(data-text)]"
                      :data-text="comment.user.name?.[0]"
                    ></div>
                    <span class="text-base font-bold">{{ comment.user.name ?? '' }}</span>
                  </Link>
                  <span
                    v-if="comment.user.id === item.data.seller.id"
                    class="mr-auto inline-block text-sm text-gray-400"
                  >
                    (出品者)
                  </span>
                </div>
                <div class="mt-1 space-y-2 rounded bg-gray-50 p-1">
                  <p class="break-words">
                    <template v-for="(line, index) in comment.content.split(/\n/)" :key="index">
                      <span>{{ line }}</span>
                      <br />
                    </template>
                  </p>
                  <p class="text-right text-xs text-gray-400">{{ comment.created_at }}</p>
                </div>
              </li>
            </ul>
          </section>

          <section>
            <form @submit.prevent="onSendComment">
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
