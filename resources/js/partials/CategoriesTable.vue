<script setup lang="ts">
import { ref, watch } from 'vue';
import type { Category, PhosphorIcon } from '@/types/category';
import CategoriesTableItem from './CategoriesTableItem.vue';

const props = defineProps<{
    categories: Category[];
}>();

const emit = defineEmits<{
    'icon-updated': [categoryId: number, icon: PhosphorIcon];
}>();

function updateCategoryIcon(categoryId: number, icon: PhosphorIcon): void {
    emit('icon-updated', categoryId, icon);
}

const selectAll = ref(false);
const selectedIds = ref<number[]>([]);

watch(selectAll, (checked) => {
    selectedIds.value = checked
        ? props.categories.map((category) => category.id)
        : [];
});

function updateSelection(id: number, checked: boolean) {
    if (checked) {
        if (!selectedIds.value.includes(id)) {
            selectedIds.value.push(id);
        }
    } else {
        selectedIds.value = selectedIds.value.filter(
            (selectedId) => selectedId !== id,
        );
    }
}
</script>

<template>
    <div class="relative">
        <header class="px-2 py-3">
            <h2 class="font-semibold text-slate-800">Categories</h2>
        </header>
        <div>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="text-sm text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-2 py-3 w-px">
                                <div class="flex items-center">
                                    <label class="inline-flex">
                                        <span class="sr-only">
                                            Select all
                                        </span>
                                        <input
                                            class="form-checkbox"
                                            v-model="selectAll"
                                            type="checkbox"
                                        />
                                    </label>
                                </div>
                            </th>
                            <th class="px-2 py-3">
                                <div class="font-semibold text-left">
                                    Name
                                </div>
                            </th>
                            <th class="px-2 py-3 w-1/4">
                                <div class="font-semibold text-left">
                                    Description
                                </div>
                            </th>
                            <th class="px-2 py-3 w-16">
                                <div class="font-semibold text-left">
                                    Icon
                                </div>
                            </th>
                            <th class="px-2 py-3">
                                <div class="font-semibold text-left">
                                    Sort
                                </div>
                            </th>
                            <th class="px-2 py-3">
                                <div class="font-semibold text-left">
                                    Status
                                </div>
                            </th>
                            <th class="px-2 py-3">
                                <div class="font-semibold text-left">
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-200">
                        <CategoriesTableItem
                            v-for="category in categories"
                            :key="category.id"
                            :category="category"
                            :checked="selectedIds.includes(category.id)"
                            @change="updateSelection"
                            @icon-updated="updateCategoryIcon"
                        />
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
