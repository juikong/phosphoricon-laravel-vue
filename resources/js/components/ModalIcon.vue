<script setup lang="ts">
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import Fuse from 'fuse.js';
import { computed, onMounted, ref, watch } from 'vue';
import type { PhosphorIcon as PhosphorIconData } from '@/types/category';
import PhosphorIcon from './PhosphorIconUtils.vue';

const props = withDefaults(
    defineProps<{
        modalOpen: boolean;
        initialIcon?: number | null;
        submitting?: boolean;
        actionError?: string | null;
    }>(),
    {
        initialIcon: null,
        submitting: false,
        actionError: null,
    },
);

const emit = defineEmits<{
    'select-icon': [iconId: number, icon: PhosphorIconData];
    'icon-modal-close': [];
}>();

const icons = ref<PhosphorIconData[]>([]);
const searchQuery = ref('');
const selectedIcon = ref<number | null>(props.initialIcon);
const loading = ref(true);
const error = ref<string | null>(null);
const selectButton = ref<HTMLButtonElement | null>(null);

const iconSearch = computed(
    () =>
        new Fuse(icons.value, {
            keys: [{ name: 'icon_name', weight: 4 }],
        }),
);

const filteredIcons = computed(() => {
    const query = searchQuery.value.trim();

    if (!query) {
        return icons.value;
    }

    return iconSearch.value.search(query).map((result) => result.item);
});

async function fetchIcons(): Promise<void> {
    loading.value = true;
    error.value = null;

    try {
        const response = await fetch('/api/phosphor-icon', {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        const data: unknown = await response.json();

        if (!Array.isArray(data)) {
            throw new Error('The icon API returned an invalid response.');
        }

        icons.value = data as PhosphorIconData[];
    } catch (requestError) {
        error.value =
            requestError instanceof Error
                ? requestError.message
                : 'Unable to load icons.';
    } finally {
        loading.value = false;
    }
}

function closeModal(): void {
    if (props.submitting) {
        return;
    }

    emit('icon-modal-close');
}

function confirmSelection(): void {
    const icon = icons.value.find((item) => item.id === selectedIcon.value);

    if (!icon) {
        return;
    }

    emit('select-icon', icon.id, icon);
}

watch(
    [() => props.modalOpen, () => props.initialIcon],
    ([isOpen, initialIcon]) => {
        if (isOpen) {
            selectedIcon.value = initialIcon;
            searchQuery.value = '';
        }
    },
);

onMounted(fetchIcons);
</script>

<template>
    <TransitionRoot as="template" :show="modalOpen">
        <Dialog
            as="div"
            class="relative z-50"
            :initial-focus="selectButton"
            @close="closeModal"
        >
            <TransitionChild
                as="template"
                enter="transition ease-out duration-200"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="transition ease-out duration-100"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-slate-900/30" aria-hidden="true" />
            </TransitionChild>

            <div class="fixed inset-0 w-screen overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-200"
                        enter-from="translate-y-4 opacity-0"
                        enter-to="translate-y-0 opacity-100"
                        leave="transition ease-in-out duration-200"
                        leave-from="translate-y-0 opacity-100"
                        leave-to="translate-y-4 opacity-0"
                    >
                        <DialogPanel
                            id="icon-modal"
                            class="mx-auto w-full max-w-2xl rounded bg-white text-left shadow-xl"
                        >
                            <div class="px-2 py-4">
                                <DialogTitle
                                    class="mb-2 px-2 font-semibold text-slate-500"
                                >
                                    Select Icon
                                </DialogTitle>

                                <div class="px-2 pb-2">
                                    <label for="icon-search" class="sr-only">
                                        Search icons
                                    </label>
                                    <input
                                        id="icon-search"
                                        v-model="searchQuery"
                                        type="search"
                                        placeholder="Search icons..."
                                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                    />
                                </div>

                                <p
                                    v-if="loading"
                                    class="p-3 text-sm text-slate-500"
                                >
                                    Loading icons...
                                </p>

                                <div v-else-if="error" class="p-3">
                                    <p class="text-sm text-red-600">
                                        {{ error }}
                                    </p>
                                    <button
                                        type="button"
                                        class="mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-700"
                                        @click="fetchIcons"
                                    >
                                        Try again
                                    </button>
                                </div>

                                <div
                                    v-else
                                    class="grid max-h-[60vh] grid-cols-6 place-items-center gap-3 overflow-y-auto p-3 sm:grid-cols-8 md:grid-cols-12"
                                >
                                    <button
                                        v-for="icon in filteredIcons"
                                        :key="icon.id"
                                        type="button"
                                        class="rounded border-2 p-1 hover:border-indigo-400"
                                        :class="
                                            selectedIcon === icon.id
                                                ? 'border-indigo-600'
                                                : 'border-white'
                                        "
                                        :aria-label="`Select ${icon.icon_name}`"
                                        :aria-pressed="selectedIcon === icon.id"
                                        @click="selectedIcon = icon.id"
                                    >
                                        <PhosphorIcon :item="icon" :size="32" />
                                    </button>

                                    <p
                                        v-if="filteredIcons.length === 0"
                                        class="col-span-full py-6 text-sm text-slate-500"
                                    >
                                        No icons found.
                                    </p>
                                </div>

                                <p
                                    v-if="actionError"
                                    class="px-2 text-sm text-red-600"
                                >
                                    {{ actionError }}
                                </p>

                                <div class="mt-3 px-2">
                                    <button
                                        ref="selectButton"
                                        type="button"
                                        class="inline-flex rounded bg-indigo-500 px-3 py-2 font-medium text-white hover:bg-indigo-600 disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="
                                            selectedIcon === null || submitting
                                        "
                                        @click="confirmSelection"
                                    >
                                        {{
                                            submitting ? 'Saving...' : 'Select'
                                        }}
                                    </button>
                                    <button
                                        type="button"
                                        class="ml-3 inline-flex rounded border border-slate-200 px-3 py-2 font-medium text-slate-600 hover:border-slate-300"
                                        :disabled="submitting"
                                        @click="closeModal"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
