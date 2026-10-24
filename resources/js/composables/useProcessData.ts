import { useForm, usePage } from '@inertiajs/vue3';
import { createFormStore } from '@/stores/formStore';

export function useProcessData() {

    const form = useForm({
        files: [] as File[],
        upload_type: null as string | null,
        unique_key: null as string | null,
        id: null as string | null,
    });

    const uniqueKey = crypto.randomUUID().replace(/-/g, '');

    const triggerFileUpload = (fileList: FileList, uploadType?: string) => {
        const useCampaignStore = createFormStore('campaign');
        const campaignStore = useCampaignStore(); 
        const campaignDataId = campaignStore.getFormDataItem('id');

        const page = usePage();
        form.files = Array.from(fileList);
        form.upload_type = uploadType ?? 'audio';
        form.unique_key = uniqueKey;
        form.id = campaignDataId;

        form.post(route('files.upload'), {
            forceFormData: true,
            onSuccess: () => {},
        });
    };

    return {
        form,
        triggerFileUpload,
        uniqueKey,
    };
}