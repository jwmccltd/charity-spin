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
  <div class="min-h-screen bg-[#008A25]">
    <div class="fixed top-[50%] left-[50%] h-2/3 z-50 grid w-2/3 max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border p-6 shadow-lg bg-white">
      <div class="p-3 flex flex-col items-center">
          <div class="progress-card">
            <h1 class="card-title">Create Campaign</h1>
              <ol class="arrow-steps">
                <li class="step active"><span>Upload Audio</span></li>
                <li class="step"><span>Add Artwork</span></li>
                <li class="step"><span>Release Details</span></li>
                <li class="step"><span>Select Charity</span></li>
                <li class="step"><span>Launch</span></li>
              </ol>
          </div>
          <div class="w-3/4">
              <h1 class="text-center mb-2">Upload the Audio file for your release</h1>
              <Dropzone @files-dropped="(files) => console.log(files)" />
          </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Card Container */
.progress-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 24px;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  max-width: 800px;
  margin: 20px auto;
}

.card-title {
  margin-top: 0;
  margin-bottom: 20px;
  color: #1e293b;
  font-size: 1.25rem;
}

/* Arrow Wrapper */
.arrow-steps {
  display: flex;
  list-style: none;
  padding: 0;
  margin: 0;
  background-color: #f1f5f9;
  border-radius: 8px;
  overflow: hidden;
}

/* Individual Steps */
.arrow-steps .step {
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1;
  position: relative;
  height: 48px;
  padding-left: 25px; /* Offset for the previous arrow point */
  padding-right: 10px;
  background-color: #e2e8f0;
  color: #64748b;
  font-weight: 600;
  font-size: 0.9rem;
  /* Creates the chevron shape */
  clip-path: polygon(calc(100% - 15px) 0%, 100% 50%, calc(100% - 15px) 100%, 0% 100%, 15px 50%, 0% 0%);
  transition: background-color 0.3s ease, color 0.3s ease;
}

/* Override left indent for the absolute first step */
.arrow-steps .step:first-child {
  padding-left: 15px;
  clip-path: polygon(calc(100% - 15px) 0%, 100% 50%, calc(100% - 15px) 100%, 0% 100%, 0% 50%, 0% 0%);
}

/* Override right arrow pointing for the absolute last step */
.arrow-steps .step:last-child {
  clip-path: polygon(100% 0%, 100% 50%, 100% 100%, 0% 100%, 15px 50%, 0% 0%);
}

/* State Styling: Completed */
.arrow-steps .step.completed {
  background-color: #dcfce7;
  color: #15803d;
}

/* State Styling: Active */
.arrow-steps .step.active {
  background-color: #EE2925;
  color: #ffffff;
}

/* Center text spacing fix */
.arrow-steps .step span {
  display: block;
  transform: translateX(-4px); /* Shifts text slightly to visually balance the arrow shape */
}

</style>