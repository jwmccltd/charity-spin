<script setup lang="ts">
import { Dropzone } from '@/components/ui/file';
import { useProcessData } from '@/composables/useProcessData';
import { useFileUploadProgress } from '@/composables/useFileUploadProgress';
import { ref, onMounted } from 'vue';
import { ProgressBar } from '@/components/ui/progress';
import { useEventStore } from '@/stores/useEventStore';
import { createFormStore } from '@/stores/formStore';
import { Mp3Player } from '@/components/ui/audio';
import { ArtworkViewer } from '@/components/ui/image';

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

const campaignId = ref<string | null>(null);

campaignStore.$subscribe((_, state) => {
    currentFileName.value = getFileName(state);
    campaignId.value = state.storedData['id'];
});

onMounted(() => {
    currentFileName.value = getFileName(campaignStore.$state);
    campaignId.value = campaignStore.$state.storedData['id'];
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
        :max-file-size="props.maxFileSize"
        @trigger-file-upload="handleFileUpload"
    />
    <div
        v-if="currentFileName && currentFileName !== 'null' && campaignId && campaignId !== 'null'"
        class="mx-auto mt-4 w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6"
    >
        <div class="flex w-full items-center">
            <div v-if="props.uploadType === 'audio'" class="flex w-full items-center">
                <Mp3Player :track-title="currentFileName" :campaign-id="campaignId"/>
            </div>
            <div v-if="props.uploadType === 'artwork'" class="flex items-center">
                <ArtworkViewer :campaign-id="campaignId" />
            </div>
        </div>
    </div>
    <ProgressBar
        v-if="showProgressBar"
        class="mx-auto mt-4 w-full max-w-3xl rounded-xl border border-slate-200 bg-white p-4 sm:p-5"
        :progress="progress"
        :status="status"
    />
</template>
