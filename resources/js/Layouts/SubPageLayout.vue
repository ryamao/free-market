<script setup lang="ts">
import LogoUrl from '../../img/logo.svg?url'
</script>

<template>
  <header class="bg-black p-4">
    <h1 class="flex h-14 items-center">
      <img :src="LogoUrl" alt="COACHTECH" class="h-6" />
    </h1>
  </header>

  <main>
    <slot />
  </main>
</template>
