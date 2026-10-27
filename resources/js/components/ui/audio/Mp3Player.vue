
<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  trackTitle: {
    type: String,
    required: true,
  },
  campaignId: {
    type: String,
    required: true,
  },
})

const audioRef = ref(null)
const isPlaying = ref(false)
const currentTime = ref(0)
const duration = ref(0)

const audioUrl = computed(() => `/api/campaign/track/${props.campaignId}`)

const togglePlay = async () => {
  if (!audioRef.value) return

  if (isPlaying.value) {
    audioRef.value.pause()
    isPlaying.value = false
  } else {
    try {
      await audioRef.value.play()
      isPlaying.value = true
    } catch (error) {
      console.error('Unable to play audio:', error)
    }
  }
}

const onTimeUpdate = () => {
  if (!audioRef.value) return
  currentTime.value = audioRef.value.currentTime
}

const onLoadedMetadata = () => {
  if (!audioRef.value) return
  duration.value = audioRef.value.duration
}

const onSeek = (event) => {
  if (!audioRef.value) return

  const seekTarget = Number.parseFloat(event.target.value)
  audioRef.value.currentTime = seekTarget
  currentTime.value = seekTarget
}

const onTrackEnded = () => {
  isPlaying.value = false
  currentTime.value = 0
}

const formatTime = (secs) => {
  if (!Number.isFinite(secs) || secs < 0) return '0:00'

  const minutes = Math.floor(secs / 60)
  const seconds = Math.floor(secs % 60)

  return `${minutes}:${String(seconds).padStart(2, '0')}`
}
</script>

<template>
  <div class="flex w-full items-center justify-center p-5 font-sans">
    <!-- Hidden native audio element -->
    <audio
      ref="audioRef"
      :src="audioUrl"
      preload="metadata"
      @timeupdate="onTimeUpdate"
      @loadedmetadata="onLoadedMetadata"
      @ended="onTrackEnded"
      @pause="isPlaying = false"
      @play="isPlaying = true"
    />

    <!-- Player card -->
    <div
      class="w-full rounded-xl bg-slate-800 p-6 text-center text-white shadow-lg"
    >
      <h3 class="mb-5 mt-0 text-xl font-semibold">
        {{ trackTitle }}
      </h3>

      <!-- Progress bar -->
      <div class="flex w-full items-center gap-3">
        <span
          class="w-10 shrink-0 text-right text-sm tabular-nums text-slate-300"
        >
          {{ formatTime(currentTime) }}
        </span>

        <input
          type="range"
          min="0"
          :max="duration || 0"
          :value="currentTime"
          :disabled="!duration"
          :aria-label="`Seek through ${trackTitle}`"
          @input="onSeek"
          class="h-2 min-w-0 flex-1 cursor-pointer accent-sky-500 disabled:cursor-not-allowed disabled:opacity-50"
        />

        <span
          class="w-10 shrink-0 text-left text-sm tabular-nums text-slate-300"
        >
          {{ formatTime(duration) }}
        </span>
      </div>

      <!-- Playback controls -->
      <div class="mt-6 flex justify-center">
        <button
          type="button"
          @click="togglePlay"
          class="rounded-full bg-sky-500 px-8 py-2.5 text-base font-bold text-white transition-colors duration-200 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-2 focus:ring-offset-slate-800 active:scale-95"
          :aria-label="isPlaying ? 'Pause audio' : 'Play audio'"
        >
          {{ isPlaying ? 'Pause' : 'Play' }}
        </button>
      </div>
    </div>
  </div>
</template>
