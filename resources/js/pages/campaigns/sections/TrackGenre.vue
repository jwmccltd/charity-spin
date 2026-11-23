<script setup lang="ts">
import { computed, ref } from 'vue';

type MusicGenre = {
    id: number;
    name: string;
    parent_id: number | null;
};

const props = defineProps<{
    musicGenres: MusicGenre[];
}>();

const emit = defineEmits<{
    stepCompleted: [step: string]
}>();

const selectedGenres = ref<number[]>([]);
const expandedCategories = ref<number[]>([]);

const categories = computed(() => {
    const roots = props.musicGenres.filter((genre) => genre.parent_id === null);

    return roots
        .map((category) => ({
            ...category,
            genres: props.musicGenres.filter((genre) => genre.parent_id === category.id),
        }))
        .filter((category) => category.genres.length > 0);
});

const selectedGenreNames = computed(() => selectedGenres.value
    .map((id) => props.musicGenres.find((genre) => genre.id === id)?.name)
    .filter((name): name is string => Boolean(name)));

const toggleGenre = (id: number) => {
    if (selectedGenres.value.includes(id)) {
        selectedGenres.value = selectedGenres.value.filter((selectedId) => selectedId !== id);
        return;
    }

    if (selectedGenres.value.length === 2) return;

    selectedGenres.value = [...selectedGenres.value, id];

    if (selectedGenres.value.length === 2) {
        emit('stepCompleted', 'details');
    }
};

const toggleCategory = (id: number) => {
    expandedCategories.value = expandedCategories.value.includes(id)
        ? expandedCategories.value.filter((categoryId) => categoryId !== id)
        : [...expandedCategories.value, id];
};
</script>

<template>
    <div class="mb-2 text-center">
        Select 2 genres that best describe your track from the expandable categories below.
    </div>
    <section class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
        <div class="mb-5 rounded-xl bg-slate-50 px-4 py-3">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-700">Genres selected</span>
                <span class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-slate-900 ring-1 ring-slate-200">
                    {{ selectedGenres.length }} / 2
                </span>
            </div>
            <div v-if="selectedGenreNames.length" class="mt-3 flex flex-wrap gap-2" aria-live="polite">
                <span
                    v-for="name in selectedGenreNames"
                    :key="name"
                    class="rounded-full bg-white px-3 py-1 text-sm font-medium text-slate-800 ring-1 ring-slate-200"
                >{{ name }}</span>
            </div>
        </div>

        <div class="space-y-5">
            <section
                v-for="category in categories"
                :key="category.id"
                class="overflow-hidden rounded-xl border border-slate-200"
            >
                <button
                    type="button"
                    class="flex w-full items-center justify-between px-4 py-4 text-left transition hover:bg-slate-50 sm:px-5"
                    :aria-expanded="expandedCategories.includes(category.id)"
                    @click="toggleCategory(category.id)"
                >
                    <span class="flex items-center gap-3">
                        <span class="text-base font-semibold text-slate-900">{{ category.name }}</span>
                        <span class="text-xs font-medium text-slate-500">{{ category.genres.length }} genres</span>
                    </span>
                    <svg
                        class="h-5 w-5 text-slate-500 transition-transform"
                        :class="{ 'rotate-180': expandedCategories.includes(category.id) }"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div v-if="expandedCategories.includes(category.id)" class="border-t border-slate-200 p-4 sm:p-5">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <label
                        v-for="genre in category.genres"
                        :key="genre.id"
                        class="flex cursor-pointer items-center gap-3 rounded-lg border px-3 py-3 transition"
                        :class="selectedGenres.includes(genre.id)
                            ? 'border-emerald-600 bg-emerald-50 text-emerald-950'
                            : 'border-slate-200 text-slate-700 hover:border-slate-400 hover:bg-slate-50'"
                    >
                        <input
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600"
                            :checked="selectedGenres.includes(genre.id)"
                            :disabled="selectedGenres.length === 2 && !selectedGenres.includes(genre.id)"
                            @change="toggleGenre(genre.id)"
                        >
                        <span class="text-sm font-medium">{{ genre.name }}</span>
                    </label>
                </div>
                </div>
            </section>
        </div>

    </section>
</template>
