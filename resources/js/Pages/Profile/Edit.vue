<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { useFileDialog } from '@vueuse/core'
import imageCompression from 'browser-image-compression'
import { ref } from 'vue'

import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import UserIcon from '@/Components/UserIcon.vue'
import SubPageLayout from '@/Layouts/SubPageLayout.vue'
import { Profile, UserData } from '@/types'

const props = defineProps<{
  user: { data: UserData }
  profile: { data: Profile }
}>()

const imageUrl = ref(props.user.data.image_url)
const forceRefreshIcon = ref(true)

const form = useForm({
  _method: 'put',
  image: null as File | null,
  name: props.user.data.name ?? '',
  postcode: props.profile.data.postcode ?? '',
  address: props.profile.data.address ?? '',
  building: props.profile.data.building ?? ''
})

const { open, onChange } = useFileDialog()

onChange((files) => {
  const image = files?.[0]
  if (image) {
    form.image = image
    imageUrl.value = URL.createObjectURL(image)
    forceRefreshIcon.value = false
  }
})

function onUpdatePostcode(value: string) {
  if (form.address !== '' || !/^\d{7}$/.test(value)) {
    return
  }

  fetch(`https://postcode.teraren.com/postcodes/${value}.json`).then((response) => {
    if (!response.ok) {
      return
    }

    response.json().then((data) => {
      form.address = ''
      if (data.prefecture) {
        form.address = data.prefecture
      }
      if (data.city) {
        form.address += data.city
      }
      if (data.suburb) {
        form.address += data.suburb
      }
    })
  })
}

async function submit() {
  if (form.image) {
    form.image = await imageCompression(form.image, { maxSizeMB: 1, maxWidthOrHeight: 480 })
  }

  form.post(route('profile.update'), {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>

<template>
  <Head title="プロフィール設定" />

  <SubPageLayout>
    <section class="mx-auto max-w-screen-md space-y-8 p-12">
      <h2 class="text-center text-2xl font-bold">プロフィール設定</h2>

      <div class="flex items-center gap-x-8">
        <UserIcon
          :user-id="user.data.id"
          :user-name="form.name"
          :image-url="imageUrl"
          :force-refresh="forceRefreshIcon"
          class="size-32 text-5xl"
        />
        <button
          type="button"
          class="inline-flex items-center justify-center rounded-md border border-emerald-600 bg-white px-6 py-1.5 text-base font-semibold uppercase tracking-widest text-emerald-600 transition duration-150 ease-in-out hover:bg-emerald-600 hover:text-white focus:bg-emerald-700 focus:text-white active:bg-emerald-800 active:text-white"
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
              pattern="\d{7}"
              title="7桁の数字で入力してください"
              class="mt-1 block w-full"
              @update:model-value="onUpdatePostcode"
            />
            <InputError class="mt-2" :message="form.errors.postcode" />
          </div>

          <div>
            <InputLabel for="address" value="住所" />
            <TextInput id="address" v-model="form.address" type="text" class="mt-1 block w-full" />
            <InputError class="mt-2" :message="form.errors.address" />
          </div>

          <div>
            <InputLabel for="building" value="建物名" />
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
