<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import LogoUrl from '../../img/logo.svg?url'
</script>

<template>
  <header class="bg-black px-4">
    <h1 class="flex h-14 items-center">
      <Link href="/">
        <img :src="LogoUrl" alt="COACHTECH" class="h-6" />
      </Link>
    </h1>
  </header>

  <slot />
</template>
