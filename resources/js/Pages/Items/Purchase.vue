<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3'
import { loadStripe, Stripe, StripeElements } from '@stripe/stripe-js'
import { onMounted, ref } from 'vue'

import Modal from '@/Components/Modal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import CommonLayout from '@/Layouts/CommonLayout.vue'
import { Item } from '@/types'

const props = defineProps<{
  item: {
    data: Item
  }
  clientSecret: string
}>()

const page = usePage()

const stripe = ref<Stripe | null>(null)
const stripeElements = ref<StripeElements | null>(null)

const paymentProcessing = ref(false)
const paymentSucceeded = ref(false)

function mountPaymentElement(elements: StripeElements) {
  const paymentElement = elements.create('payment', {
    layout: 'accordion',
    paymentMethodOrder: ['card', 'konbini', 'customer_balance']
  })
  paymentElement.mount('#payment-element')
}

function mountAddressElement(elements: StripeElements) {
  const user = page.props.auth.user
  const postalCode = user.postcode ? `${user.postcode.slice(0, 3)}-${user.postcode.slice(3)}` : null
  const addressElement = elements.create('address', {
    mode: 'shipping',
    defaultValues: {
      name: user.name,
      address: {
        country: 'JP',
        postal_code: postalCode,
        state: user.prefecture,
        line1: user.address,
        line2: user.building
      }
    }
  })
  addressElement.mount('#address-element')
}

async function handleSubmit() {
  if (!stripe.value || !stripeElements.value) {
    return
  }

  paymentProcessing.value = true

  const { error: validateError } = await stripeElements.value.submit()
  if (validateError) {
    paymentProcessing.value = false
    return
  }

  const { error: confirmError } = await stripe.value.confirmPayment({
    elements: stripeElements.value,
    redirect: 'if_required'
  })

  if (confirmError) {
    alert(confirmError.message)
    paymentProcessing.value = false
  } else {
    paymentSucceeded.value = true
  }
}

function handleClose() {
  router.visit(route('items.show', { item: props.item.data }), { replace: true })
}

onMounted(async () => {
  stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
  if (!stripe.value) {
    return
  }

  stripeElements.value = stripe.value.elements({
    clientSecret: props.clientSecret
  })

  mountPaymentElement(stripeElements.value)
  mountAddressElement(stripeElements.value)
})
</script>

<template>
  <Head :title="item.data.name" />

  <CommonLayout>
    <div class="mx-auto my-16 grid max-w-screen-md space-y-8 px-8">
      <div class="flex gap-x-8">
        <div>
          <img :src="item.data.image_url" alt="" class="h-32" />
        </div>
        <div class="flex flex-col justify-evenly">
          <h2 class="text-2xl font-bold">{{ item.data.name }}</h2>
          <p class="text-xl">¥{{ item.data.price.toLocaleString() }}</p>
        </div>
      </div>

      <div class="space-y-4">
        <h3 class="text-lg font-bold">支払い方法</h3>
        <div id="payment-element"></div>
      </div>

      <div class="space-y-4">
        <h3 class="col-span-2 text-lg font-bold">配送先</h3>
        <div id="address-element"></div>
      </div>

      <div>
        <PrimaryButton
          type="button"
          class="w-full py-1.5"
          :class="{ 'opacity-25': paymentProcessing }"
          :disabled="paymentProcessing"
          @click.prevent="handleSubmit"
        >
          購入する
        </PrimaryButton>
      </div>
    </div>
  </CommonLayout>

  <Modal :show="paymentProcessing" :closeable="paymentSucceeded" @close="handleClose">
    <div class="m-16">
      <template v-if="paymentSucceeded">
        <p class="my-4 text-center text-2xl font-bold">購入が完了しました</p>
        <div class="flex items-center justify-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            class="size-32 text-green-500"
          >
            <path
              fill="currentColor"
              d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"
            ></path>
          </svg>
        </div>
        <div class="my-8 flex justify-center">
          <PrimaryButton type="button" class="px-8 py-0.5" @click.prevent="handleClose">
            商品詳細に戻る
          </PrimaryButton>
        </div>
      </template>
      <template v-else>
        <p class="my-4 text-center text-2xl font-bold">購入処理中...</p>
        <div class="flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="size-32">
            <radialGradient
              id="a10"
              cx=".66"
              fx=".66"
              cy=".3125"
              fy=".3125"
              gradientTransform="scale(1.5)"
            >
              <stop offset="0" stop-color="#FF156D"></stop>
              <stop offset=".3" stop-color="#FF156D" stop-opacity=".9"></stop>
              <stop offset=".6" stop-color="#FF156D" stop-opacity=".6"></stop>
              <stop offset=".8" stop-color="#FF156D" stop-opacity=".3"></stop>
              <stop offset="1" stop-color="#FF156D" stop-opacity="0"></stop>
            </radialGradient>
            <circle
              transform-origin="center"
              fill="none"
              stroke="url(#a10)"
              stroke-width="15"
              stroke-linecap="round"
              stroke-dasharray="200 1000"
              stroke-dashoffset="0"
              cx="100"
              cy="100"
              r="70"
            >
              <animateTransform
                type="rotate"
                attributeName="transform"
                calcMode="spline"
                dur="2"
                values="360;0"
                keyTimes="0;1"
                keySplines="0 0 1 1"
                repeatCount="indefinite"
              ></animateTransform>
            </circle>
            <circle
              transform-origin="center"
              fill="none"
              opacity=".2"
              stroke="#FF156D"
              stroke-width="15"
              stroke-linecap="round"
              cx="100"
              cy="100"
              r="70"
            ></circle>
          </svg>
        </div>
      </template>
    </div>
  </Modal>
</template>
