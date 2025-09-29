<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Category } from '@/types';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catégories',
        href: '/',
    },
];

const props = defineProps<{
    categories: Category[];
}>();
console.log(props.categories);
</script>
<template>
    <Head title="Liste des catégories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">Liste des catégories</h1>
                <Link href="/categories/create" class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    Créer une nouvelle catégorie
                </Link>
                <div v-if="!props.categories.length" class="text-gray-500">Aucune catégorie actuellement.</div>
                <ul v-else class="space-y-4">
                    <li v-for="category in props.categories" :key="category.id" class="rounded-lg border p-4 shadow transition hover:shadow-lg">
                        <Link :href="`/categories/${category.id}`" class="text-2xl font-semibold text-blue-600 hover:underline">
                            {{ category.name }}
                        </Link>
                        <p class="mt-2 break-words whitespace-pre-wrap text-gray-700">
                            {{ category.description?.slice(0, 250) }}<span v-if="category.description?.length > 250"> ...</span>
                        </p>
                        <p class="mt-2 text-sm break-words whitespace-pre-wrap text-gray-700">Nombre d'articles : {{ category.posts.length }}</p>
                        <div class="mt-4 flex space-x-2">
                            <Link :href="`/categories/${category.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                                Éditer
                            </Link>
                            <button
                                @click="Inertia.delete(`/categories/${category.id}`, { preserveScroll: true })"
                                class="cursor-pointer rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                            >
                                Supprimer
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
