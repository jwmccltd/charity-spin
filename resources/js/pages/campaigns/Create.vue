<script setup lang="ts">
import { ref } from 'vue';
import CreateSection from './CreateSection.vue';
import UploadAudio from './sections/UploadAudio.vue';
import UploadArtwork from './sections/UploadArtwork.vue';
import TrackGenre from './sections/TrackGenre.vue';
import TrackDetails from './sections/TrackDetails.vue';
import SelectCharity from './sections/SelectCharity.vue';

const completedSteps = ref<string[]>([]);
const selectedCharityId = ref<string | null>(null);

const markCompleted = (step: string) => {
    if (!completedSteps.value.includes(step)) {
        completedSteps.value.push(step);
    }
};

const props = defineProps<{
    musicGenres: {
        id: number;
        name: string;
        parent_id: number | null;
    }[];
}>();

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
      <template #genres>
        <div>
          <TrackGenre :music-genres="props.musicGenres" @stepCompleted="markCompleted"/>
        </div>
      </template>
      <template #details>
        <div>
          <TrackDetails @stepCompleted="markCompleted" />
        </div>
      </template>
      <template #charity>
        <div>
          <SelectCharity v-model="selectedCharityId" @stepCompleted="markCompleted" />
        </div>
      </template>
    </CreateSection>
</template>
