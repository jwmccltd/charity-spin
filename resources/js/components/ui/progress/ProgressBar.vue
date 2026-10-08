<script setup>
defineProps({
  progress: {
    type: Number,
    default: 0
  }
})
</script>

<template>
  <div class="w-full">
    <div class="h-3 overflow-hidden rounded-full bg-gray-200">
      <div
        class="h-full rounded-full bg-blue-600 transition-all duration-300"
        :style="{ width: `${Math.min(100, Math.max(0, progress))}%` }"
      />
    </div>
  </div>
</template>
