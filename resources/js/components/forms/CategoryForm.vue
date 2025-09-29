<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catégories',
        href: '/',
    },
];

import type { Category } from '@/types';
const props = defineProps<{
    category?: Category;
    readonly?: boolean;
}>();

const form = useForm<Pick<Category, 'name' | 'description'>>({
    name: props.category?.name ?? '',
    description: props.category?.description ?? '',
});

const submit = () => {
    if (props.readonly) {
        return;
    } else if (props.category) {
        form.put(`/categories/${props.category.id}`, { preserveScroll: true });
    } else {
        form.post('/categories', { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="props.category ? 'Édition d\'une catégorie' : 'Création d\'une catégorie'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">{{ props.category ? "Édition d'une catégorie" : "Création d'une catégorie" }}</h1>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required
                            :readonly="props.readonly"
                        />
                        <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="5"
                            class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            :readonly="props.readonly"
                        ></textarea>
                    </div>
                    <div v-if="props.readonly && props.category" class="mt-4 flex space-x-2">
                        <Link :href="`/categories/${props.category.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                            Éditer
                        </Link>
                        <button
                            @click="Inertia.delete(`/categories/${props.category.id}`, { preserveScroll: true })"
                            class="cursor-pointer rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                        >
                            Supprimer
                        </button>
                    </div>
                    <div v-else class="mt-4 flex space-x-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="cursor-pointer rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 disabled:opacity-50"
                        >
                            {{ props.category ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
