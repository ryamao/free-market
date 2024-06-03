<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { useFileDialog } from '@vueuse/core'
import imageCompression from 'browser-image-compression'
import { ref } from 'vue'

import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import UserIcon from '@/Components/UserIcon.vue'
import SubPageLayout from '@/Layouts/SubPageLayout.vue'

const page = usePage()

const imageUrl = ref(page.props.auth.user.image_url)
const forceRefreshIcon = ref(true)

const form = useForm({
  _method: 'put',
  image: null as File | null,
  name: page.props.auth.user.name ?? '',
  postcode: page.props.auth.user.postcode ?? '',
  prefecture: page.props.auth.user.prefecture ?? '',
  address: page.props.auth.user.address ?? '',
  building: page.props.auth.user.building ?? ''
})

const { open, onChange } = useFileDialog({
  accept: 'image/*',
  multiple: false
})

onChange((files) => {
  const image = files?.[0]
  if (image) {
    form.image = image
    imageUrl.value = URL.createObjectURL(image)
    forceRefreshIcon.value = false
  }
})

async function onUpdatePostcode(value: string) {
  if (!/^\d{3}-?\d{4}$/.test(value)) {
    return
  }

  value = value.replace('-', '')

  const response = await fetch(`https://postcode.teraren.com/postcodes/${value}.json`)
  if (!response.ok) {
    return
  }

  const data = await response.json()

  form.prefecture = ''
  form.address = ''
  form.building = ''

  if (data.prefecture) {
    form.prefecture = data.prefecture
  }
  if (data.city) {
    form.address += data.city
  }
  if (data.suburb) {
    form.address += data.suburb
  }
}

async function submit() {
  if (form.image) {
    form.image = await imageCompression(form.image, { maxSizeMB: 1, maxWidthOrHeight: 480 })
  }

  if (form.postcode) {
    form.postcode = form.postcode.replace('-', '')
  }

  form.post(route('profile.update'), {
    onSuccess: () => {
      form.reset()
    }
  })
}

const prefectures = [
  '北海道',
  '青森県',
  '岩手県',
  '宮城県',
  '秋田県',
  '山形県',
  '福島県',
  '茨城県',
  '栃木県',
  '群馬県',
  '埼玉県',
  '千葉県',
  '東京都',
  '神奈川県',
  '新潟県',
  '富山県',
  '石川県',
  '福井県',
  '山梨県',
  '長野県',
  '岐阜県',
  '静岡県',
  '愛知県',
  '三重県',
  '滋賀県',
  '京都府',
  '大阪府',
  '兵庫県',
  '奈良県',
  '和歌山県',
  '鳥取県',
  '島根県',
  '岡山県',
  '広島県',
  '山口県',
  '徳島県',
  '香川県',
  '愛媛県',
  '高知県',
  '福岡県',
  '佐賀県',
  '長崎県',
  '熊本県',
  '大分県',
  '宮崎県',
  '鹿児島県',
  '沖縄県'
]
</script>

<template>
  <Head title="プロフィール設定" />

  <SubPageLayout>
    <section class="mx-auto max-w-screen-sm space-y-8 p-12">
      <h2 class="text-center text-2xl font-bold">プロフィール設定</h2>

      <div class="flex items-center gap-x-8">
        <UserIcon
          :user-id="$page.props.auth.user.id"
          :user-name="form.name"
          :image-url="imageUrl"
          :force-refresh="forceRefreshIcon"
          class="size-32 text-5xl"
        />
        <button
          type="button"
          class="inline-flex items-center justify-center rounded-md border-2 border-emerald-600 bg-white px-6 py-1.5 text-base font-semibold uppercase tracking-widest text-emerald-600 transition duration-150 ease-in-out hover:bg-emerald-600 hover:text-white focus:bg-emerald-700 focus:text-white active:bg-emerald-800 active:text-white"
          @click="() => open()"
        >
          画像を選択する
        </button>
      </div>

      <form class="space-y-16" @submit.prevent="submit">
        <div class="space-y-8">
          <div>
            <InputLabel for="name" value="ユーザー名" />
            <TextInput
              id="name"
              v-model="form.name"
              type="text"
              class="mt-1 block w-full"
              required
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <div>
            <InputLabel for="postcode" value="郵便番号" />
            <TextInput
              id="postcode"
              v-model="form.postcode"
              type="text"
              pattern="\d{3}-?\d{4}"
              class="mt-1 block w-full"
              @update:model-value="onUpdatePostcode"
            />
            <InputError class="mt-2" :message="form.errors.postcode" />
          </div>

          <div>
            <InputLabel for="prefecture" value="都道府県" />
            <select
              id="prefecture"
              v-model="form.prefecture"
              class="mt-1 block w-full rounded-md border-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="" disabled>都道府県を選択してください</option>
              <option v-for="prefecture in prefectures" :key="prefecture" :value="prefecture">
                {{ prefecture }}
              </option>
            </select>
            <InputError class="mt-2" :message="form.errors.address" />
          </div>

          <div>
            <InputLabel for="address" value="市区町村など" />
            <TextInput id="address" v-model="form.address" type="text" class="mt-1 block w-full" />
            <InputError class="mt-2" :message="form.errors.address" />
          </div>

          <div>
            <InputLabel for="building" value="建物名、部屋番号など" />
            <TextInput
              id="building"
              v-model="form.building"
              type="text"
              class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="form.errors.building" />
          </div>
        </div>

        <div>
          <PrimaryButton
            type="submit"
            class="w-full py-1.5"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            更新する
          </PrimaryButton>
        </div>
      </form>
    </section>
  </SubPageLayout>
</template>
