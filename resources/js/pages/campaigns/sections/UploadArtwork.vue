<script setup lang="ts">
import UploadFile from './UploadFile.vue';

const emit = defineEmits<{
    stepCompleted: [step: string]
}>();

</script>

<template>
    <UploadFile
        upload-type="artwork"
        title="Upload the artwork for your release. Images will be converted to JPEG format."
        allowed-extensions=".jpg, .jpeg, .png, .gif"
        additional-info="Image must be square and minimum 1200 x 1200 pixels"
        max-file-size="10MB"
        @fileProcessed="emit('stepCompleted', 'artwork')"
    />
</template>