<script setup lang="ts">
import { ref } from 'vue';
import CreateSection from './CreateSection.vue';
import UploadAudio from './sections/UploadAudio.vue';
import UploadArtwork from './sections/UploadArtwork.vue';

const completedSteps = ref<string[]>([]);

const markCompleted = (step: string) => {
    if (!completedSteps.value.includes(step)) {
        completedSteps.value.push(step);
    }
};

</script>

<template>
    <CreateSection :completed-steps="completedSteps">
      <template #audio>
        <div>
          <UploadAudio @stepCompleted="markCompleted" />
        </div>
      </template>
      <template #artwork>
        <div>
          <UploadArtwork @stepCompleted="markCompleted"/>
        </div>
      </template>
    </CreateSection>
</template>
