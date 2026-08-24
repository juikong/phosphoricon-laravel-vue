<script setup lang="ts">
import { ref } from 'vue';
import type {
    Category,
    PhosphorIcon as PhosphorIconData,
} from '@/types/category';
import ModalIcon from '../components/ModalIcon.vue';
import PhosphorIcon from '../components/PhosphorIconUtils.vue';

const props = defineProps<{
    category: Category;
    checked: boolean;
}>();

const emit = defineEmits<{
    change: [id: number, checked: boolean];
    'icon-updated': [categoryId: number, icon: PhosphorIconData];
}>();

const modalOpen = ref(false);
const savingIcon = ref(false);
const updateError = ref<string | null>(null);

function openIconModal(): void {
    updateError.value = null;
    modalOpen.value = true;
}

async function updateIcon(
    iconId: number,
    icon: PhosphorIconData,
): Promise<void> {
    savingIcon.value = true;
    updateError.value = null;

    try {
        const response = await fetch('/api/category-edit', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: props.category.id,
                phosphor_icon_id: iconId,
            }),
        });

        const data = (await response.json().catch(() => null)) as {
            message?: string;
        } | null;

        if (!response.ok) {
            throw new Error(
                data?.message ??
                    `Request failed with status ${response.status}`,
            );
        }

        emit('icon-updated', props.category.id, icon);
        modalOpen.value = false;
    } catch (requestError) {
        updateError.value =
            requestError instanceof Error
                ? requestError.message
                : 'Unable to update the category icon.';
    } finally {
        savingIcon.value = false;
    }
}

function statusColor(status: number): string {
    return status === 1
        ? 'bg-emerald-100 text-emerald-600'
        : 'bg-amber-100 text-amber-600';
}

function statusText(status: number): string {
    return status === 1 ? 'Active' : 'Inactive';
}
</script>

<template>
    <tr>
        <td class="px-2 py-3">
            <div class="flex items-center">
                <label class="inline-flex">
                    <span class="sr-only">Select</span>
                    <input
                        class="form-checkbox"
                        type="checkbox"
                        :checked="checked"
                        @change="
                            emit(
                                'change',
                                category.id,
                                ($event.target as HTMLInputElement).checked,
                            )
                        "
                    />
                </label>
            </div>
        </td>
        <td class="px-2 py-3">
            <div class="font-medium text-slate-800">{{ category.name }}</div>
        </td>
        <td class="px-2 py-3">
            <div>{{ category.description }}</div>
        </td>
        <td class="px-2 py-3">
            <div>
                <PhosphorIcon
                    v-if="category.phosphor_icon"
                    :item="category.phosphor_icon"
                    :size="16"
                />
            </div>
        </td>
        <td class="px-2 py-3">
            <div class="font-medium text-sky-500">{{ category.sort }}</div>
        </td>
        <td class="px-2 py-3">
            <div
                :class="[
                    'inline-flex rounded-full px-2.5 py-0.5 text-center font-medium',
                    statusColor(category.status),
                ]"
            >
                {{ statusText(category.status) }}
            </div>
        </td>
        <td class="px-2 py-3">
            <div class="space-x-1">
                <button
                    type="button"
                    class="rounded-full text-slate-400 hover:text-slate-500"
                    aria-controls="icon-modal"
                    aria-haspopup="dialog"
                    @click="openIconModal"
                >
                    <span class="sr-only">Edit</span>
                    <svg class="h-8 w-8 fill-current" viewBox="0 0 32 32">
                        <path
                            d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z"
                        />
                    </svg>
                </button>
            </div>
        </td>
    </tr>

    <ModalIcon
        :modal-open="modalOpen"
        :initial-icon="category.phosphor_icon_id"
        :submitting="savingIcon"
        :action-error="updateError"
        @select-icon="updateIcon"
        @icon-modal-close="modalOpen = false"
    />
</template>
