import { useForm } from '@inertiajs/vue3';

export function useUploadFile() {
    const form = useForm({
        files: [] as File[],
        upload_type: null as string | null,
        unique_key: null as string | null,
    });

    const uniqueKey = crypto.randomUUID().replace(/-/g, '');

    const triggerFileUpload = (fileList: FileList, uploadType?: string) => {
        form.files = Array.from(fileList);
        form.upload_type = uploadType ?? 'audio';
        form.unique_key = uniqueKey;

        form.post(route('files.upload'), {
            forceFormData: true,
            onSuccess: () => form.reset(),
        });
    };

    return {
        form,
        triggerFileUpload,
        uniqueKey,
    };
}