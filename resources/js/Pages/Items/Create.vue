<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { useDropZone, useFileDialog } from '@vueuse/core'
import imageCompression from 'browser-image-compression'
import { ref } from 'vue'

import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import Modal from '@/Components/Modal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import SubPageLayout from '@/Layouts/SubPageLayout.vue'

defineProps<{
  conditions: string[]
}>()

const form = useForm({
  image: null as File | null,
  categories: '',
  condition: '',
  name: '',
  description: '',
  price: ''
})

const imagePreviewUrl = ref<string | null>(null)

function onChangeImage(files: File[] | FileList | null) {
  const image = files?.[0]
  if (image) {
    form.image = image
    imagePreviewUrl.value = URL.createObjectURL(image)
  }
}

const { open, onChange } = useFileDialog({
  accept: 'image/png, image/jpeg',
  multiple: false
})

onChange(onChangeImage)

const dropZoneRef = ref<HTMLElement | null>(null)

useDropZone(dropZoneRef, {
  dataTypes: ['image/png', 'image/jpeg'],
  onDrop: onChangeImage
})

const confirmingItemCreation = ref(false)

function confirmItemCreation() {
  confirmingItemCreation.value = true
}

function closeModal() {
  confirmingItemCreation.value = false
}

async function createNewItem() {
  if (!form.image) {
    return
  }

  form.image = await imageCompression(form.image, { maxSizeMB: 1, maxWidthOrHeight: 1024 })

  form.post('/items', {
    onError: () => {
      confirmingItemCreation.value = false
      alert('商品の出品に失敗しました')
    },
    onSuccess: () => {
      confirmingItemCreation.value = false
      alert('商品を出品しました')
    }
  })
}
</script>

<template>
  <Head title="出品" />

  <SubPageLayout>
    <section class="mx-auto my-8 max-w-screen-sm">
      <h2 class="my-8 text-center text-2xl font-bold">商品の出品</h2>

      <form class="space-y-16" @submit.prevent="confirmItemCreation">
        <div class="space-y-6">
          <h3 class="border-b border-gray-400 pb-1 text-xl font-bold text-gray-500">商品の詳細</h3>
          <div>
            <InputLabel for="categories">カテゴリー</InputLabel>
            <TextInput
              id="categories"
              v-model="form.categories"
              type="text"
              class="w-full"
              required
            />
            <InputError class="mt-2" :message="form.errors.categories" />
          </div>
          <div>
            <InputLabel for="condition">商品の状態</InputLabel>
            <select
              id="condition"
              v-model="form.condition"
              class="w-full rounded-md border border-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
              required
            >
              <option value="" disabled selected>選択してください</option>
              <option v-for="condition in conditions" :key="condition" :value="condition">
                {{ condition }}
              </option>
            </select>
            <InputError class="mt-2" :message="form.errors.condition" />
          </div>
        </div>

        <div class="space-y-6">
          <h3 class="border-b border-gray-400 pb-1 text-xl font-bold text-gray-500">
            商品名と説明
          </h3>
          <div>
            <InputLabel for="name">商品名</InputLabel>
            <TextInput id="name" v-model="form.name" type="text" class="w-full" required />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="description">商品の説明</InputLabel>
            <textarea
              id="description"
              v-model="form.description"
              class="w-full rounded-md border border-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
              rows="5"
              required
            />
            <InputError class="mt-2" :message="form.errors.description" />
          </div>
        </div>

        <div class="space-y-6">
          <h3 class="border-b border-gray-400 pb-1 text-xl font-bold text-gray-500">販売価格</h3>
          <div>
            <InputLabel for="price">販売価格</InputLabel>
            <div class="relative">
              <TextInput
                id="price"
                v-model="form.price"
                type="text"
                class="w-full pl-6"
                pattern="\d+"
                required
              />
              <div
                class="absolute left-0 top-0 flex h-full w-7 items-center justify-center text-base font-bold"
              >
                ¥
              </div>
            </div>
            <InputError class="mt-2" :message="form.errors.price" />
          </div>
        </div>

        <div class="space-y-6">
          <h3 class="border-b border-gray-400 pb-1 text-xl font-bold text-gray-500">商品画像</h3>
          <div>
            <div ref="dropZoneRef" class="relative aspect-square">
              <img
                v-if="imagePreviewUrl"
                :src="imagePreviewUrl"
                alt="商品画像"
                class="absolute left-0 top-0 size-full rounded-md border object-contain"
              />
              <div
                v-else
                class="absolute left-0 top-0 size-full border-4 border-dashed border-gray-300"
              ></div>
              <button
                type="button"
                class="absolute left-1/2 top-1/2 inline-flex -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-md border-2 border-emerald-600 bg-white px-6 py-1.5 text-base font-semibold uppercase tracking-widest text-emerald-600 transition duration-150 ease-in-out hover:bg-emerald-600 hover:text-white"
                @click="() => open()"
              >
                画像を選択する
              </button>
            </div>
            <InputError class="mt-2" :message="form.errors.image" />
          </div>
        </div>

        <div>
          <PrimaryButton type="submit" class="w-full py-1.5">出品する</PrimaryButton>
        </div>
      </form>
    </section>

    <Modal :show="confirmingItemCreation" @close="closeModal">
      <section class="m-6">
        <h2 class="my-4 text-center text-xl font-bold">以下の商品を出品します</h2>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <img
              v-if="imagePreviewUrl"
              :src="imagePreviewUrl"
              alt=""
              class="w-full object-contain"
            />
          </div>
          <div>
            <div class="space-y-4">
              <div>
                <h3 class="text-lg font-bold">商品名</h3>
                <p>{{ form.name }}</p>
              </div>
              <div>
                <h3 class="text-lg font-bold">販売価格</h3>
                <p>¥{{ Number(form.price).toLocaleString() }}</p>
              </div>
              <div>
                <h3 class="text-lg font-bold">商品の説明</h3>
                <p class="break-words">
                  <template
                    v-for="(line, index) in form.description.split(/\n/)"
                    :key="'description-' + index"
                  >
                    <span>{{ line }}</span>
                    <br />
                  </template>
                </p>
              </div>
              <div>
                <h3 class="text-lg font-bold">カテゴリー</h3>
                <ul>
                  <li v-for="category in form.categories.split(/\s+/)" :key="category">
                    <span>{{ category }}</span>
                  </li>
                </ul>
              </div>
              <div>
                <h3 class="text-lg font-bold">商品の状態</h3>
                <p>{{ form.condition }}</p>
              </div>
            </div>
          </div>
          <div class="col-span-2">
            <PrimaryButton
              type="button"
              class="w-full py-0.5"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              @click="createNewItem"
            >
              出品する
            </PrimaryButton>
            <button
              type="button"
              class="mt-2 w-full text-center text-gray-500 hover:text-gray-700 hover:underline"
              @click="closeModal"
            >
              キャンセル
            </button>
          </div>
        </div>
      </section>
    </Modal>
  </SubPageLayout>
</template>
