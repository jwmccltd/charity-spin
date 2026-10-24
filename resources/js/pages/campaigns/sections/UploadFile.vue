<script setup lang="ts">
import { Dropzone } from '@/components/ui/file';
import { useProcessData } from '@/composables/useProcessData';
import { useFileUploadProgress } from '@/composables/useFileUploadProgress';
import { ref, onMounted } from 'vue';
import { ProgressBar } from '@/components/ui/progress';
import { useEventStore } from '@/stores/useEventStore';
import { createFormStore } from '@/stores/formStore';
import { FileMusic } from '@lucide/vue';

const props = defineProps<{
    uploadType: string;
    title: string;
    allowedExtensions?: string;
    additionalInfo?: string;
    maxFileSize?: string;
}>();

const emit = defineEmits<{
    fileProcessed: [uploadType?: string]
}>();

const { triggerFileUpload, uniqueKey } = useProcessData();
const { progress, status, errorMessage, startPolling } = useFileUploadProgress();
const showProgressBar = ref(false);
const fileUploadMode = ref('upload');

const eventStore = useEventStore();
const useCampaignStore = createFormStore('campaign');
const campaignStore = useCampaignStore(); 

const currentFileName = ref<string | null>(null);
const getFileName = (state: any) =>
    state.storedData[`original_${props.uploadType}_file_name`];

campaignStore.$subscribe((_, state) => {
    currentFileName.value = getFileName(state);
});

onMounted(() => {
    currentFileName.value = getFileName(campaignStore.$state);
});

const handleFileUpload = async (files: FileList) => {
    try {
        eventStore.triggerEvent('lock-campaign-progress');

        // 1. Fire the file upload trigger
        await triggerFileUpload(files, props.uploadType);
        
        // 2. Start polling for the file upload progress
        showProgressBar.value = true;
        fileUploadMode.value = 'uploaded';
        emit('fileProcessed', props.uploadType);
        
        startPolling(uniqueKey);
    } catch (error) {
        console.error('File upload failed:', error);
    }
};
</script>

<template>
    <Dropzone
        :mode="fileUploadMode"
        :upload-type="props.uploadType"
        :title="props.title"
        :allowed-extensions="props.allowedExtensions"
        :additional-info="props.additionalInfo"
        @trigger-file-upload="handleFileUpload"
    />
    <div class="flex items-center justify-between w-full h-full mt-7" v-if="currentFileName && currentFileName !== 'null'">
        <div class="flex items-center">
            <FileMusic :size="48"/> {{ currentFileName }}
        </div>
    </div>
    <ProgressBar class="mt-4" v-if="showProgressBar" :progress="progress" :status="status" />
</template>