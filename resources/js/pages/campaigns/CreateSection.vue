<script setup lang="ts">
import { ref, watch } from 'vue';
import { useEventStore } from '@/stores/useEventStore';

const eventStore = useEventStore();

watch (
  () => eventStore.lastEvent,
  (newEvent) => {
    if (!newEvent) return;

    // TypeScript automatically narrows down the payload type based on the name check
    if (newEvent.name === 'lock-campaign-progress') {
      isDisabled.value = true;
    }

    if (newEvent.name === 'release-campaign-progress') {
      isDisabled.value = false;
    }
  }
);

const steps = [
    { section: 'audio', label: 'Upload Audio' },
    { section: 'artwork', label: 'Add Artwork' },
    { section: 'release-details', label: 'Release Details' },
    { section: 'charity', label: 'Select Charity' },
    { section: 'launch', label: 'Launch' },
];

const activeStep = ref('audio');

const setActiveStep = (section: string) => {
    activeStep.value = section;
};

const isDisabled = ref(false);

</script>

<template>
    <div class="min-h-screen bg-[#008A25]">
        <!-- Main Panel -->
        <div
            class="fixed inset-4 z-50 mx-auto my-auto grid w-full h-full max-w-[calc(100%-2rem)] max-h-[calc(100%-2rem)] gap-4 overflow-auto rounded-lg border bg-white p-4 shadow-lg md:w-2/3 md:h-2/3 sm:p-6"
        >
            <div class="flex flex-col items-center p-2 sm:p-3">
                <!-- Progress Navigation -->
                <div class="progress-card">
                    <h1 class="card-title">
                        Create Campaign
                    </h1>

                    <ol class="arrow-steps">
                        <li
                            v-for="step in steps"
                            :key="step.section"
                            class="step"
                            :class="{ active: activeStep === step.section, 'cursor-pointer' : !isDisabled, 'cursor-not-allowed' : isDisabled  }"
                            @click="setActiveStep(step.section)"
                        >
                            <span>{{ step.label }}</span>
                        </li>
                    </ol>
                </div>

                <!-- Upload Section -->
                <div class="w-full max-w-3xl">
                    <slot :name="activeStep"></slot>
                </div>

                <!-- Next Last Buttons -->
            </div>
        </div>
    </div>
</template>

<style scoped>
/* -------------------------------------------------------
   Progress Card
------------------------------------------------------- */

.progress-card {
    width: 100%;
    margin: 0 auto 20px;
    padding: 24px;
    background: #ffffff;
    border-radius: 12px;
}

.card-title {
    margin-top: 0;
    margin-bottom: 20px;

    color: #1e293b;

    font-size: 1.25rem;
    font-weight: 600;
}

/* -------------------------------------------------------
   Arrow Navigation
------------------------------------------------------- */

.arrow-steps {
    display: flex;
    flex-direction: column;
    gap: 8px;

    width: 100%;

    margin: 0;
    padding: 0;

    list-style: none;
}

/* Individual Steps */

.arrow-steps .step {
    display: flex;
    align-items: center;
    justify-content: flex-start;

    width: 100%;
    min-height: 48px;

    padding: 0 16px;

    background-color: #e2e8f0;
    color: #64748b;

    font-size: 0.9rem;
    font-weight: 600;

    border-radius: 8px;

    transition:
        background-color 0.3s ease,
        color 0.3s ease;
}

/* Completed */

.arrow-steps .step.completed {
    background-color: #dcfce7;
    color: #15803d;
}

/* Active */

.arrow-steps .step.active {
    background-color: #00adf1;
    color: #ffffff;
}

/* Step Text */

.arrow-steps .step span {
    display: block;
}

/* -------------------------------------------------------
   Desktop - Keep Original Arrow Layout
------------------------------------------------------- */

@media (min-width: 768px) {
    .arrow-steps {
        flex-direction: row;
        gap: 0;
    }

    .arrow-steps .step {
        position: relative;

        flex: 1;
        width: auto;

        height: 48px;
        min-height: 48px;

        justify-content: center;

        padding-left: 25px;
        padding-right: 10px;

        border-radius: 0;

        clip-path: polygon(
            calc(100% - 15px) 0%,
            100% 50%,
            calc(100% - 15px) 100%,
            0% 100%,
            15px 50%,
            0% 0%
        );
    }

    /* First step */

    .arrow-steps .step:first-child {
        padding-left: 15px;

        clip-path: polygon(
            calc(100% - 15px) 0%,
            100% 50%,
            calc(100% - 15px) 100%,
            0% 100%,
            0% 50%,
            0% 0%
        );
    }

    /* Last step */

    .arrow-steps .step:last-child {
        clip-path: polygon(
            100% 0%,
            100% 50%,
            100% 100%,
            0% 100%,
            15px 50%,
            0% 0%
        );
    }

    /* Balance text inside arrows */

    .arrow-steps .step span {
        transform: translateX(-4px);
    }
}

/* -------------------------------------------------------
   Mobile
------------------------------------------------------- */

@media (max-width: 767px) {
    .progress-card {
        padding: 12px;
    }

    .card-title {
        margin-bottom: 16px;
    }

    .arrow-steps .step {
        min-height: 44px;
    }
}
</style>
