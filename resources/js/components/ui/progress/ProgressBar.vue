<script setup>

import Spinner from '@/components/ui/spinner/Spinner.vue';

defineProps({
  progress: {
    type: Number,
    default: 0
  },
  status: {
    type: String,
    required: true,
  }
})
</script>

<template>
  <div class="w-full h-6">
    <div class="mb-1 flex items-center justify-center gap-2">
      <div>
         {{ status.charAt(0).toUpperCase() + status.slice(1) }}
      </div>
      <Spinner v-show="status !== 'completed'"/>
    </div>
    <div class="h-full overflow-hidden rounded-full bg-gray-200">
      <div
        class="h-full rounded-full bg-blue-600 transition-all duration-300"
        :style="{ width: `${Math.min(100, Math.max(0, progress))}%` }"
      />
    </div>
  </div>
</template>
