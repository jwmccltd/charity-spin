<script setup lang="ts">
import { ref } from 'vue';

const emit = defineEmits<{
  (e: 'files-dropped', files: FileList): void
}>();

const isDragging = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

// Trigger file selection window when clicking the dropzone
const openFilePicker = () => {
  fileInput.value?.click();
};

// Handle files selected via the standard file input
const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    emit('files-dropped', target.files);
  }
};

// Handle files dropped via drag-and-drop
const handleDrop = (event: DragEvent) => {
  isDragging.value = false;
  if (event.dataTransfer?.files && event.dataTransfer.files.length > 0) {
    emit('files-dropped', event.dataTransfer.files);
  }
};
</script>

<template>
  <div
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="handleDrop"
    @click="openFilePicker"
    :class="[
      'flex flex-col items-center justify-center w-full h-full border-2 border-dashed rounded-lg cursor-pointer transition-colors duration-200',
      isDragging 
        ? 'border-primary bg-primary/10' 
        : 'border-muted-foreground/30 hover:border-primary/50 bg-card hover:bg-accent/10'
    ]"
  >
    <!-- Hidden native file input -->
    <input
      accept=".mp3, .aac, .m4a, .m4b, .mp4, .ogg, .oga, .opus, .wma, .wav, .flac, .aif, .aiff, .ape"
      type="file"
      ref="fileInput"
      class="hidden"
      multiple
      @change="handleFileSelect"
    />

    <!-- Dropzone UI Content -->
    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
      <svg 
        class="w-8 h-8 mb-3 text-muted-foreground" 
        xmlns="http://w3.org" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke-width="1.5" 
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
      </svg>
      <p class="mb-2 text-sm text-foreground font-medium">
        <span class="text-primary underline">Click to upload</span> or drag and drop
      </p>
      <p class="text-xs text-muted-foreground">Any file type up to 10MB</p>
    </div>
  </div>
  <div class="text-center mt-2">Accepted file formats: mp3, .aac, .m4a, .m4b, .mp4, .ogg, .oga, .opus, .wma, .wav, .flac, .aif, .aiff, .ape</div>
</template>
