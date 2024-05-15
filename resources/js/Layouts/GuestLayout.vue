<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import LogoUrl from '../../img/logo.svg?url'
</script>

<template>
  <header class="bg-black p-4">
    <Link href="/" class="block h-6">
      <img :src="LogoUrl" alt="COACHTECH" class="h-full" />
    </Link>
  </header>

  <slot />
</template>
