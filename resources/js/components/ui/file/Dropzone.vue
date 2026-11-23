<script setup lang="ts">
import { ref } from 'vue';

const emit = defineEmits<{
  triggerFileUpload: [files: FileList]
}>();

const isDragging = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);
const fileName = ref<string>('');

// Trigger file selection window when clicking the dropzone
const openFilePicker = () => {
  fileInput.value?.click();
};

// Handle files selected via the standard file input
const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    fileName.value = target.files[0].name;

    emit('triggerFileUpload', target.files);
  }
};

// Handle files dropped via drag-and-drop
const handleDrop = (event: DragEvent) => {
  isDragging.value = false;
  if (event.dataTransfer?.files && event.dataTransfer.files.length > 0) {
    fileName.value = event.dataTransfer.files[0].name;

    emit('triggerFileUpload', event.dataTransfer.files);
  }
};

const getAcceptableExtensions = () => {
  return props.allowedExtensions;
};

const getAdditionalInfo = () => {
  return props.additionalInfo;
};

const getAllowedUploadSize = () => {  
  return props.maxFileSize;
};

const props = defineProps({
  uploadType: {
    type: String,
    default: null,
  },
  title: {
    type: String,
    default: null,
  },
  mode: {
    type: String,
    default: 'upload'
  },
  allowedExtensions: {
    type: String,
    default: '',
  },
  additionalInfo: {
    type: String,
    default: '',
  },
  maxFileSize: {
    type: String,
    default: '10MB',
  }
});
</script>

<template>
  <section class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
    <p v-if="title" class="mb-5 text-sm leading-6 text-slate-600">
      {{ title }}
    </p>

    <div
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="handleDrop"
      @click="openFilePicker"
      @keydown.enter.prevent="openFilePicker"
      @keydown.space.prevent="openFilePicker"
      :class="[
        'flex min-h-52 w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed px-5 py-8 text-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2',
        isDragging
          ? 'border-emerald-600 bg-emerald-50'
          : 'border-slate-300 bg-slate-50 hover:border-emerald-500 hover:bg-emerald-50/50'
      ]"
      role="button"
      tabindex="0"
      :aria-label="`Choose ${uploadType} file to upload`"
    >
      <input
        :accept="getAcceptableExtensions()"
        type="file"
        ref="fileInput"
        class="hidden"
        multiple
        @change="handleFileSelect"
      />

      <span class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-white text-emerald-700 shadow-sm ring-1 ring-slate-200">
        <svg
          class="h-6 w-6"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.7"
          stroke="currentColor"
          aria-hidden="true"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
        </svg>
      </span>

      <p class="text-sm font-semibold text-slate-900">
        <span class="text-emerald-700">Click to upload</span> or drag and drop
      </p>
      <p class="mt-1 text-xs text-slate-500">Maximum file size: {{ getAllowedUploadSize() }}</p>
    </div>

    <div class="mt-4 space-y-2 text-sm text-slate-600">
      <p class="rounded-lg bg-slate-50 px-3 py-2.5">
        <span class="font-medium text-slate-700">Accepted formats:</span>
        {{ getAcceptableExtensions() }}
      </p>
      <p v-if="getAdditionalInfo()" class="rounded-lg bg-slate-50 px-3 py-2.5">
        {{ getAdditionalInfo() }}
      </p>
    </div>
  </section>
</template>
