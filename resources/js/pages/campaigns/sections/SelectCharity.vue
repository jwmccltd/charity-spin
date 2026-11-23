<script setup lang="ts">
import { computed } from 'vue';
import { usePlatformIcon } from '@/composables/platformIcons';

type Charity = {
    id: string;
    name: string;
    cause: string;
    description: string;
    website: string;
};

const props = defineProps<{
    modelValue: string | null;
}>();

const emit = defineEmits<{
    'update:modelValue': [id: string];
    stepCompleted: [step: string];
}>();

const charityDetails: Charity[] = [
    {
        id: 'cancer-research-uk',
        name: 'Cancer Research UK',
        cause: 'Cancer research',
        description: 'Funds research and works to prevent, diagnose and treat cancer.',
        website: 'https://www.cancerresearchuk.org/',
    },
    {
        id: 'british-red-cross',
        name: 'British Red Cross',
        cause: 'Crisis and emergencies',
        description: 'Provides support to people in crisis, in the UK and around the world.',
        website: 'https://www.redcross.org.uk/',
    },
    {
        id: 'macmillan-cancer-support',
        name: 'Macmillan Cancer Support',
        cause: 'Cancer support',
        description: 'Supports people living with cancer and those close to them.',
        website: 'https://www.macmillan.org.uk/',
    },
    {
        id: 'nspcc',
        name: 'NSPCC',
        cause: 'Children and young people',
        description: 'Works to protect children and help prevent child abuse.',
        website: 'https://www.nspcc.org.uk/',
    },
    {
        id: 'mind',
        name: 'Mind',
        cause: 'Mental health',
        description: 'Provides advice and support to people experiencing mental health problems.',
        website: 'https://www.mind.org.uk/',
    },
    {
        id: 'rnli',
        name: 'RNLI',
        cause: 'Water safety and rescue',
        description: 'Saves lives at sea and around the UK’s coasts and waterways.',
        website: 'https://rnli.org/',
    },
    {
        id: 'shelter',
        name: 'Shelter',
        cause: 'Housing and homelessness',
        description: 'Helps people facing homelessness and poor housing in England and Scotland.',
        website: 'https://www.shelter.org.uk/',
    },
    {
        id: 'rspca',
        name: 'RSPCA',
        cause: 'Animal welfare',
        description: 'Rescues, rehabilitates and rehomes animals, and campaigns for their welfare.',
        website: 'https://www.rspca.org.uk/',
    },
    {
        id: 'save-the-children',
        name: 'Save the Children UK',
        cause: 'Children worldwide',
        description: 'Works to support children and protect their rights around the world.',
        website: 'https://www.savethechildren.org.uk/',
    },
    {
        id: 'alzheimers-society',
        name: "Alzheimer's Society",
        cause: 'Dementia support and research',
        description: 'Supports people affected by dementia and funds research into the condition.',
        website: 'https://www.alzheimers.org.uk/',
    },
];

const charities = charityDetails.map((charity) => ({
    ...charity,
    ...usePlatformIcon(charity.website),
}));

const selectedCharity = computed(() => charities.find((charity) => charity.id === props.modelValue));

const selectCharity = (charity: Charity) => {
    emit('update:modelValue', charity.id);
    emit('stepCompleted', 'charity');
};
</script>

<template>
    <div class="mb-2 text-center text-sm text-slate-600">
        Choose the charity you would like your campaign to support.
    </div>

    <section class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
        <div class="mb-5 flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
            <span class="text-sm font-medium text-slate-700">Selected charity</span>
            <span class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-slate-900 ring-1 ring-slate-200">
                {{ selectedCharity ? '1 selected' : 'Choose one' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <article
                v-for="charity in charities"
                :key="charity.id"
                class="relative flex flex-col rounded-xl border p-4 transition"
                :class="modelValue === charity.id
                    ? 'border-emerald-600 bg-emerald-50/60 ring-1 ring-emerald-600'
                    : 'border-slate-200 bg-white hover:border-slate-400 hover:bg-slate-50'"
            >
                <button
                    type="button"
                    class="flex flex-1 flex-col text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                    :aria-pressed="modelValue === charity.id"
                    @click="selectCharity(charity)"
                >
                    <span class="flex w-full items-start justify-between gap-3">
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-200 bg-white p-1.5">
                                <img
                                    v-if="charity.icon.value.type === 'image'"
                                    :src="charity.icon.value.src"
                                    :alt="`${charity.name} logo`"
                                    class="h-full w-full object-contain"
                                    @error="charity.onImageError"
                                >
                                <svg
                                    v-else
                                    class="h-6 w-6 text-slate-500"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path fill-rule="evenodd" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm6.9 9h-3.1a15.7 15.7 0 0 0-1.2-5.4 8 8 0 0 1 4.3 5.4ZM12 4c.9 1.2 1.6 3.8 1.8 7h-3.6c.2-3.2.9-5.8 1.8-7Zm-2.6 1.6A15.7 15.7 0 0 0 8.2 11H5.1a8 8 0 0 1 4.3-5.4ZM5.1 13h3.1a15.7 15.7 0 0 0 1.2 5.4A8 8 0 0 1 5.1 13Zm6.9 7c-.9-1.2-1.6-3.8-1.8-7h3.6c-.2 3.2-.9 5.8-1.8 7Zm2.6-1.6a15.7 15.7 0 0 0 1.2-5.4h3.1a8 8 0 0 1-4.3 5.4Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-left text-base font-semibold text-slate-900">{{ charity.name }}</span>
                                <span class="mt-1 block text-left text-xs font-medium text-emerald-800">{{ charity.cause }}</span>
                            </span>
                        </span>
                        <span
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border"
                            :class="modelValue === charity.id
                                ? 'border-emerald-600 bg-emerald-600 text-white'
                                : 'border-slate-300 bg-white text-transparent'"
                            aria-hidden="true"
                        >
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.2 7.26a1 1 0 0 1-1.42.006l-3.8-3.74a1 1 0 1 1 1.404-1.426l3.09 3.04 6.506-6.56a1 1 0 0 1 1.414.006Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                    <span class="mt-3 text-sm leading-5 text-slate-600">{{ charity.description }}</span>
                </button>
                <a
                    :href="charity.website"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-4 inline-flex w-fit items-center gap-1 text-sm font-medium text-slate-600 underline decoration-slate-300 underline-offset-4 hover:text-emerald-800"
                >
                    Visit website
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M11.5 3a.75.75 0 0 0 0 1.5h2.94l-7.22 7.22a.75.75 0 1 0 1.06 1.06l7.22-7.22V8.5a.75.75 0 0 0 1.5 0v-4.75A.75.75 0 0 0 16.25 3H11.5Z" />
                        <path d="M5 5.75A1.75 1.75 0 0 1 6.75 4h2a.75.75 0 0 1 0 1.5h-2a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-2a.75.75 0 0 1 1.5 0v2a1.75 1.75 0 0 1-1.75 1.75h-7.5A1.75 1.75 0 0 1 5 13V5.75Z" />
                    </svg>
                </a>
            </article>
        </div>

        <p v-if="selectedCharity" class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" aria-live="polite">
            {{ selectedCharity.name }} is selected for this campaign.
        </p>
    </section>
</template>
