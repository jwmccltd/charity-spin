<script setup lang="ts">
import { Dropzone } from '@/components/ui/file';
import { useUploadFile } from '@/composables/useUploadFile';
import { useFileUploadProgress } from '@/composables/useFileUploadProgress';
import { watch, ref } from 'vue';
import { ProgressBar } from '@/components/ui/progress';
import { useEventStore } from '@/stores/useEventStore';

const props = defineProps<{
    uploadType?: string;
    title: string;
}>();

const { triggerFileUpload, uniqueKey } = useUploadFile();
const { progress, status, errorMessage, startPolling } = useFileUploadProgress();
const showProgressBar = ref(false);
const fileUploadMode = ref('upload');

const eventStore = useEventStore();

const handleFileUpload = async (files: FileList) => {
    try {
        eventStore.triggerEvent('lock-campaign-progress');

        // 1. Fire the file upload trigger
        await triggerFileUpload(files);
        
        // 2. Start polling for the file upload progress
        showProgressBar.value = true;
        fileUploadMode.value = 'uploaded';
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
        :mode="fileUploadMode"
        :upload-type="props.uploadType"
        :title="props.title"
        @trigger-file-upload="handleFileUpload"
    />
    <ProgressBar class="mt-4" v-if="showProgressBar" :progress="progress" :status="status" />
</template>