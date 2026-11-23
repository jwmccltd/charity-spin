
<script setup>
import { ref, watch, onBeforeUnmount } from 'vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';

const props = defineProps({
  progress: {
    type: Number,
    default: 0
  },
  status: {
    type: String,
    required: true
  }
});

const showProgress = ref(true);
let timeout;

watch(
  () => props.status,
  (status) => {
    clearTimeout(timeout);

    if (status === 'completed') {
      timeout = setTimeout(() => {
        showProgress.value = false;
      }, 2000);
    } else {
      showProgress.value = true;
    }
  },
  { immediate: true }
);

onBeforeUnmount(() => {
  clearTimeout(timeout);
});
</script>


<template>
  <div class="w-full">
    <Transition name="fade">
      <div v-if="showProgress">
        <div class="mb-1 flex items-center justify-center gap-2">
          <div>
            {{ status.charAt(0).toUpperCase() + status.slice(1) }}
          </div>
          <Spinner v-show="status !== 'completed'" />
        </div>

        <div class="h-6 overflow-hidden rounded-full bg-gray-200">
          <div
            class="h-full rounded-full bg-emerald-600 transition-all duration-300"
            :style="{
              width: `${Math.min(100, Math.max(0, progress))}%`
            }"
          />
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.fade-leave-active {
  transition: opacity 500ms ease;
}

.fade-leave-to {
  opacity: 0;
}
</style>
