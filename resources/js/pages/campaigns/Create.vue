<script setup lang="ts">
import CreateSection from './CreateSection.vue';
import UploadAudio from './sections/UploadAudio.vue';
import UploadArtwork from './sections/UploadArtwork.vue';
</script>

<template>
    <CreateSection>
      <template #audio>
        <div>
          <UploadAudio/>
        </div>
      </template>
      <template #artwork>
        <div>
          <UploadArtwork/>
        </div>
      </template>
    </CreateSection>
</template>
