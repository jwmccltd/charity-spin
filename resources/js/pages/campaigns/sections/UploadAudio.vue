<script setup lang="ts">
import UploadFile from './UploadFile.vue';

const emit = defineEmits<{
    stepCompleted: [step: string]
}>();

</script>

<template>
    <UploadFile
        upload-type="audio"
        title="Upload the audio file for your campaign. Audio will be converted to MP3 format for streaming. Audio files must be minimum 16-bit, 44.1kHz, stereo and mp3s must be 320 kbps."
        allowed-extensions=".mp3, .aac, .m4a, .m4b, .mp4, .ogg, .oga, .opus, .wma, .wav, .flac, .aif, .aiff, .ape"
        max-file-size="100MB"
        @fileProcessed="emit('stepCompleted', 'audio')"
    />
</template>