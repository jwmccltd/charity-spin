<script setup lang="ts">
import { Dropzone } from '@/components/ui/file';
import { useUploadFile } from '@/composables/useUploadFile';
import { useFileUploadProgress } from '@/composables/useFileUploadProgress';
import { watch } from 'vue';
import { ProgressBar } from '@/components/ui/progress';

const props = defineProps<{
    uploadType?: string;
    title: string;
}>();

const { triggerFileUpload, uniqueKey } = useUploadFile();
const { progress, status, errorMessage, startPolling } = useFileUploadProgress();

const handleFileUpload = async (files: FileList) => {
    try {
        // 1. Fire the file upload trigger
        await triggerFileUpload(files);
        
        // 2. Start polling for the file upload progress
        startPolling(uniqueKey);
    } catch (error) {
        console.error('File upload failed:', error);
    }
};

watch(progress, (newProgress, oldProgress) => {
    console.log('Progress changed:', newProgress, '→', oldProgress);
});
</script>

<template>
    <Dropzone
        :upload-type="props.uploadType"
        :title="props.title"
        @trigger-file-upload="handleFileUpload"
    />
    <ProgressBar v-if="status === 'processing'" :progress="progress" />
</template>