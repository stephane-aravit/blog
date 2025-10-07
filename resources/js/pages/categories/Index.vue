<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catégories',
        href: '/',
    },
];

const props = defineProps<{
    // On récupère les catégories filtrés, triés et paginés
    categories: {
        data: any[];
        current_page: number;
        last_page: number;
        total: number;
    };
    // Filtres actifs sur la page
    filters: {
        search?: string;
        sort_order?: string;
    };
}>();

const search = ref(props.filters.search || '');
const sortOrder = ref(props.filters.sort_order || 'asc');

// On recharge la page avec les nouveaux filtres détectés
function applyFilters() {
    router.get(
        'categories',
        {
            search: search.value,
            sort_order: sortOrder.value,
        },
        { preserveState: true },
    );
}

// On recharge la page en allant à la page demandée
function goToPage(page: number) {
    router.get(
        'categories',
        {
            page,
            search: search.value,
            sort_order: sortOrder.value,
        },
        { preserveState: true },
    );
}
</script>
<template>
    <Head title="Liste des catégories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">Liste des catégories ({{ props.categories.total }})</h1>
                <div class="mb-4 flex justify-between">
                    <Link href="/categories/create" class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        Créer une nouvelle catégorie
                    </Link>
                    <div class="inline-block">
                        <input
                            type="search"
                            name="category_search"
                            v-model="search"
                            placeholder="Filtre par mot-clés"
                            class="mr-2 ml-2 inline-block rounded border border-blue-500 px-4 py-2 hover:ring-1 hover:ring-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                        />
                        <div class="relative inline-block">
                            <select
                                name="category_sort_order"
                                v-model="sortOrder"
                                class="mr-2 ml-2 inline-block appearance-none rounded border border-blue-500 py-2 pr-8 pl-4 hover:ring-1 hover:ring-blue-500 focus:outline-none"
                            >
                                <option value="asc">Ascendant</option>
                                <option value="desc">Descendant</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <button @click="applyFilters" class="ml-2 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                            Filtrer
                        </button>
                    </div>
                </div>
                <div v-if="!props.categories.total" class="text-gray-500">Aucune catégorie actuellement.</div>
                <ul v-else class="space-y-4">
                    <li v-for="category in props.categories.data" :key="category.id" class="rounded-lg border p-4 shadow transition hover:shadow-lg">
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
                                @click="router.delete(`/categories/${category.id}`, { preserveScroll: true })"
                                class="cursor-pointer rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                            >
                                Supprimer
                            </button>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 flex justify-center">
                    <button
                        v-for="page in props.categories.last_page"
                        :key="page"
                        @click="goToPage(page)"
                        :class="[
                            'mr-2 ml-2 inline-block cursor-pointer rounded border-blue-500 px-4 py-2 hover:ring-1 hover:ring-blue-500',
                            props.categories.current_page == page ? 'border-2 font-bold' : 'border',
                        ]"
                    >
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
