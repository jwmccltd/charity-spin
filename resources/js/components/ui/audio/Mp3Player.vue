<template>
  <div class="audio-player">
    <!-- Hidden native audio element -->
    <audio 
      ref="audioRef" 
      :src="audioSource"
      @timeupdate="onTimeUpdate"
      @loadedmetadata="onLoadedMetadata"
      @ended="onTrackEnded"
    ></audio>

    <div class="player-card">
      <h3 class="track-title">{{ trackTitle }}</h3>
      
      <div class="flex items-center">
        <!-- Progress Bar Slider -->
        <div class="progress-container h-10">
            <span class="time-stamp">{{ formatTime(currentTime) }}</span>
            <input 
            type="range" 
            min="0" 
            :max="duration" 
            :value="currentTime" 
            @input="onSeek"
            class="seek-bar"
            />
            <span class="time-stamp">{{ formatTime(duration) }}</span>
        </div>

        <!-- Controls -->
        <div class="controls h-10">
            <button @click="togglePlay" class="btn-play">
            {{ isPlaying ? 'Pause' : 'Play' }}
            </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
    trackTitle: {
        type: String,
        required: true,
    },
    audioSource: {
        type: String,
        required: true,
    }
})

// State management
const audioRef = ref(null)
const isPlaying = ref(false)
const currentTime = ref(0)
const duration = ref(0)

// Toggle play and pause states
const togglePlay = () => {
  if (!audioRef.value) return

  if (isPlaying.value) {
    audioRef.value.pause()
    isPlaying.value = false
  } else {
    audioRef.value.play()
    isPlaying.value = true
  }
}

// Keep track slider sync'd as audio plays
const onTimeUpdate = () => {
  currentTime.value = audioRef.value.currentTime
}

// Capture actual length once media loads
const onLoadedMetadata = () => {
  duration.value = audioRef.value.duration
}

// Scrape timeline manually if user drags range slider
const onSeek = (event) => {
  const seekTarget = parseFloat(event.target.value)
  audioRef.value.currentTime = seekTarget
  currentTime.value = seekTarget
}

// Reset UI state when the track runs out
const onTrackEnded = () => {
  isPlaying.value = false
  currentTime.value = 0
}

// Format seconds into MM:SS blocks
const formatTime = (secs) => {
  if (isNaN(secs)) return '0:00'
  const minutes = Math.floor(secs / 60)
  const seconds = Math.floor(secs % 60)
  return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`
}
</script>

<style scoped>
.audio-player {
  display: flex;
  justify-content: center;
  align-items: middle;
  font-family: sans-serif;
  margin: 20px;
}

.player-card {
  background: #2c3e50;
  color: #fff;
  padding: 24px;
  border-radius: 12px;
  width: 100%;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  text-align: center;
}

.track-title {
  margin-top: 0;
  margin-bottom: 20px;
  font-size: 1.2rem;
}

.progress-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.seek-bar {
  flex-grow: 1;
  margin: 0 10px;
  cursor: pointer;
}

.time-stamp {
  font-size: 0.85rem;
  font-variant-numeric: tabular-nums;
  width: 40px;
}

.btn-play {
  background: #00adf1;
  border: none;
  color: white;
  padding: 10px 24px;
  font-size: 1rem;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.2s ease;
}

.btn-play:hover {
  background: #35495e;
}
</style>
