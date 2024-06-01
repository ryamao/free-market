<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'
import { loadStripe, Stripe, StripeElements } from '@stripe/stripe-js'
import { onMounted, ref } from 'vue'

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

const processingSubmit = ref(false)

function mountPaymentElement(elements: StripeElements) {
  const paymentElement = elements.create('payment', {
    layout: 'accordion',
    paymentMethodOrder: ['card', 'konbini', 'customer_balance']
  })
  paymentElement.mount('#payment-element')
}

function mountAddressElement(elements: StripeElements) {
  const user = page.props.auth.user
  const postalCode = `${user.postcode.slice(0, 3)}-${user.postcode.slice(3)}`
  const addressElement = elements.create('address', {
    mode: 'shipping',
    defaultValues: {
      name: user.name,
      address: {
        country: 'JP',
        postal_code: postalCode,
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

  processingSubmit.value = true

  const { error: validateError } = await stripeElements.value.submit()
  if (validateError) {
    processingSubmit.value = false
    return
  }

  const { error: confirmError } = await stripe.value.confirmPayment({
    elements: stripeElements.value,
    confirmParams: {
      return_url: route('items.show', { item: props.item.data })
    }
  })
  if (confirmError) {
    alert(confirmError.message)
    processingSubmit.value = false
    return
  }
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
          class="w-full py-0.5"
          :class="{ 'opacity-25': processingSubmit }"
          :disabled="processingSubmit"
          @click.prevent="handleSubmit"
        >
          購入する
        </PrimaryButton>
      </div>
    </div>
  </CommonLayout>
</template>
