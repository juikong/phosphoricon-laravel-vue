<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import type { Category, PhosphorIcon } from '@/types/category';
import CategoriesTable from '../partials/CategoriesTable.vue';

const categories = ref<Category[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

function updateCategoryIcon(categoryId: number, icon: PhosphorIcon): void {
    const category = categories.value.find((item) => item.id === categoryId);

    if (!category) {
        return;
    }

    category.phosphor_icon_id = icon.id;
    category.phosphor_icon = icon;
}

onMounted(async () => {
    try {
        const response = await fetch('/api/categories', {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        const data: unknown = await response.json();

        if (!Array.isArray(data)) {
            throw new Error('The categories API returned an invalid response.');
        }

        categories.value = data as Category[];
    } catch (requestError) {
        error.value =
            requestError instanceof Error
                ? requestError.message
                : 'Unable to load categories.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <Head title="Categories" />
    <div>
        <main>
            <div class="py-10">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <p v-if="loading" class="text-sm text-slate-500">
                        Loading categories...
                    </p>
                    <p v-else-if="error" class="text-sm text-red-600">
                        {{ error }}
                    </p>
                    <CategoriesTable
                        v-else
                        :categories="categories"
                        @icon-updated="updateCategoryIcon"
                    />
                </div>
            </div>
        </main>
    </div>
</template>
