<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Dropzone } from '@/components/ui/file';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

const isOpen = defineModel<boolean>('isOpen');
isOpen.value = true;

const form = useForm({
  files: [] as File[],
});

const onFilesDropped = (fileList: FileList) => {
  // Convert FileList to an array and store it in form data
  form.files = Array.from(fileList);
};

const submit = () => {
  form.post(route('files.upload'), {
    forceFormData: true, // Ensures Inertia sends it as multipart/form-data
    onSuccess: () => form.reset(),
  });
};

</script>
<template>
    <Dialog class="p-3" :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="w-[calc(100%-1.5rem)] max-w-none sm:max-w-3/4 sm:h-3/4">
            <DialogHeader class="flex items-center justify-center p-3">
                <Dropzone @files-dropped="(files) => console.log(files)" />
            </DialogHeader>
        </DialogContent>
    </Dialog>            
</template>