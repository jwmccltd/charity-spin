<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue';
import { usePlatformIcon, type IconResult, type PlatformName } from '@/composables/platformIcons';

type LinkField = {
    field: { id: number; url: string };
    icon: ReturnType<typeof usePlatformIcon>['icon'];
    onImageError: ReturnType<typeof usePlatformIcon>['onImageError'];
};

const emit = defineEmits<{
    stepCompleted: [step: string]
}>();

const links = ref<LinkField[]>([]);
let nextId = 1;

const addLink = () => {
    if (links.value.length >= 10) return;

    const field = reactive({ id: nextId++, url: '' });
    const { icon, onImageError } = usePlatformIcon(() => field.url);

    links.value.push({ field, icon, onImageError });
};

addLink();

const hasLink = computed(() => links.value.some(({ field }) => field.url.trim().length > 0));
const populatedLinkCount = computed(() => links.value.filter(({ field }) => field.url.trim()).length);

watch(hasLink, (hasValue) => {
    if (hasValue) emit('stepCompleted', 'details');
});

const platformLabels: Record<PlatformName, string> = {
    twitter: 'X / Twitter',
    instagram: 'Instagram',
    linkedin: 'LinkedIn',
    github: 'GitHub',
    youtube: 'YouTube',
    tiktok: 'TikTok',
    globe: 'Website',
};

const iconLabel = (icon: IconResult) => icon.type === 'svg'
    ? platformLabels[icon.name]
    : 'Website';

const iconColor: Record<PlatformName, string> = {
    twitter: 'text-slate-900',
    instagram: 'text-pink-600',
    linkedin: 'text-blue-700',
    github: 'text-slate-900',
    youtube: 'text-red-600',
    tiktok: 'text-slate-900',
    globe: 'text-slate-500',
};
</script>

<template>
    <div class="mb-2 text-center text-sm text-slate-600">
        Add links to your website and social profiles. You can add up to 10.
    </div>

    <section class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
        <div class="mb-5 flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
            <span class="text-sm font-medium text-slate-700">Links added</span>
            <span class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-slate-900 ring-1 ring-slate-200">
                {{ populatedLinkCount }} / 10
            </span>
        </div>

        <div class="space-y-3">
            <div v-for="(link, index) in links" :key="link.field.id">
                <label :for="`track-link-${link.field.id}`" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Link {{ index + 1 }}
                </label>
                <div class="flex items-center gap-3">
                    <input
                        :id="`track-link-${link.field.id}`"
                        v-model="link.field.url"
                        type="url"
                        inputmode="url"
                        autocomplete="url"
                        placeholder="https://example.com/your-profile"
                        class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20"
                    >

                    <div
                        v-if="link.field.url.trim()"
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50"
                        :title="iconLabel(link.icon.value)"
                        role="img"
                        :aria-label="iconLabel(link.icon.value)"
                    >
                        <img
                            v-if="link.icon.value.type === 'image'"
                            :src="link.icon.value.src"
                            :alt="`${iconLabel(link.icon.value)} icon`"
                            class="h-6 w-6 rounded-sm"
                            @error="link.onImageError"
                        >
                        <svg
                            v-else
                            viewBox="0 0 24 24"
                            class="h-6 w-6"
                            :class="iconColor[link.icon.value.name]"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path v-if="link.icon.value.name === 'instagram'" fill-rule="evenodd" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7Zm5 3a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.5-3a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5Z" clip-rule="evenodd" />
                            <path v-else-if="link.icon.value.name === 'youtube'" d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1C24 15.9 24 12 24 12s0-3.9-.5-5.8ZM9.6 15.6V8.4l6.2 3.6-6.2 3.6Z" />
                            <path v-else-if="link.icon.value.name === 'linkedin'" d="M20.4 2H3.6A1.6 1.6 0 0 0 2 3.6v16.8A1.6 1.6 0 0 0 3.6 22h16.8a1.6 1.6 0 0 0 1.6-1.6V3.6A1.6 1.6 0 0 0 20.4 2ZM8 19H5V9h3v10ZM6.5 7.6A1.7 1.7 0 1 1 6.5 4a1.7 1.7 0 0 1 0 3.6ZM19 19h-3v-4.9c0-1.2 0-2.7-1.7-2.7s-2 1.3-2 2.6V19h-3V9h2.9v1.4h.1a3.2 3.2 0 0 1 2.9-1.6c3.1 0 3.8 2 3.8 4.6V19Z" />
                            <path v-else-if="link.icon.value.name === 'github'" fill-rule="evenodd" d="M12 .8a11.2 11.2 0 0 0-3.5 21.8c.6.1.8-.3.8-.6v-2.1c-3.2.7-3.9-1.4-3.9-1.4-.5-1.3-1.3-1.6-1.3-1.6-1-.7.1-.7.1-.7 1.1.1 1.7 1.2 1.7 1.2 1 .1.7 2.7 3.8 1.9.1-.7.4-1.2.7-1.5-2.6-.3-5.3-1.3-5.3-5.7 0-1.2.4-2.2 1.2-3-.1-.3-.5-1.5.1-3 0 0 1-.3 3.1 1.2a10.7 10.7 0 0 1 5.6 0c2.1-1.5 3.1-1.2 3.1-1.2.6 1.5.2 2.7.1 3 .8.8 1.2 1.8 1.2 3 0 4.4-2.7 5.4-5.3 5.7.4.3.8 1 .8 2v3c0 .3.2.7.8.6A11.2 11.2 0 0 0 12 .8Z" clip-rule="evenodd" />
                            <path v-else-if="link.icon.value.name === 'twitter'" d="M18.9 2h3.3L15 10.3 23.5 22h-6.7l-5.2-7.2L5.4 22H2l7.7-8.9L1.5 2h6.9l4.7 6.6L18.9 2Zm-1.2 18h1.8L7.4 3.9H5.5L17.7 20Z" />
                            <path v-else-if="link.icon.value.name === 'tiktok'" d="M19.6 5.3a5.4 5.4 0 0 1-3.4-3.4A7 7 0 0 1 15.9 0h-4.1v15.2a2.9 2.9 0 1 1-2-2.8V8.2a7.6 7.6 0 1 0 6.2 7.4V7.9a9.4 9.4 0 0 0 5.5 1.8V5.6c-.7 0-1.3-.1-1.9-.3Z" />
                            <path v-else-if="link.icon.value.name === 'globe'" fill-rule="evenodd" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm6.9 9h-3.1a15.7 15.7 0 0 0-1.2-5.4 8 8 0 0 1 4.3 5.4ZM12 4c.9 1.2 1.6 3.8 1.8 7h-3.6c.2-3.2.9-5.8 1.8-7Zm-2.6 1.6A15.7 15.7 0 0 0 8.2 11H5.1a8 8 0 0 1 4.3-5.4ZM5.1 13h3.1a15.7 15.7 0 0 0 1.2 5.4A8 8 0 0 1 5.1 13Zm6.9 7c-.9-1.2-1.6-3.8-1.8-7h3.6c-.2 3.2-.9 5.8-1.8 7Zm2.6-1.6a15.7 15.7 0 0 0 1.2-5.4h3.1a8 8 0 0 1-4.3 5.4Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <button
            v-if="links.length < 10"
            type="button"
            class="mt-5 inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2"
            @click="addLink"
        >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10 4a.75.75 0 0 1 .75.75v4.5h4.5a.75.75 0 0 1 0 1.5h-4.5v4.5a.75.75 0 0 1-1.5 0v-4.5h-4.5a.75.75 0 0 1 0-1.5h4.5v-4.5A.75.75 0 0 1 10 4Z" />
            </svg>
            Add another link
        </button>
    </section>
</template>
