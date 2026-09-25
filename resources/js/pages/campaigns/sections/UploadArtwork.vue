<script setup lang="ts">
import { Dropzone } from '@/components/ui/file';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    files: [] as File[],
});

const onFilesDropped = (fileList: FileList) => {
    form.files = Array.from(fileList);
};

const submit = () => {
    form.post(route('files.upload'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};
</script>
<template>
    <Dropzone
        @files-dropped="onFilesDropped"
        upload-type="release-artwork"
        title="Upload the artwork for your release"
    />
</template>