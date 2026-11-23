
<script setup lang="ts">
import { computed, ref } from 'vue';

interface Props {
  campaignId: number | string;
}

const props = defineProps<Props>();

const imageError = ref(false);
const loading = ref(true);

const imageUrl = computed(
  () => `/api/campaign/artwork/${props.campaignId}`
);

function handleLoad() {
  loading.value = false;
  imageError.value = false;
}

function handleError() {
  loading.value = false;
  imageError.value = true;
}
</script>

<template>
  <div class="mx-auto w-full max-w-1/3">
    <p v-if="loading && !imageError" class="text-sm text-gray-500">
      Loading image...
    </p>

    <img
      v-if="!imageError"
      :src="imageUrl"
      alt="Campaign image"
      class="block h-auto w-full rounded-lg object-contain"
      @load="handleLoad"
      @error="handleError"
    />

    <p v-if="imageError" class="text-sm text-red-600">
      Unable to load campaign image.
    </p>
  </div>
</template>
