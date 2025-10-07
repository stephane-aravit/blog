<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Commentaires',
        href: '/',
    },
];

const props = defineProps<{
    // On récupère les commentaires filtrés, triés et paginés
    comments: {
        data: any[];
        current_page: number;
        last_page: number;
        total: number;
    };
    // Filtres actifs sur la page
    filters: {
        search?: string;
        sort_field?: string;
        sort_order?: string;
    };
}>();

const search = ref(props.filters.search || '');
const sortField = ref(props.filters.sort_field || 'created_at');
const sortOrder = ref(props.filters.sort_order || 'desc');

// On recharge la page avec les nouveaux filtres détectés
function applyFilters() {
    router.get(
        'comments',
        {
            search: search.value,
            sort_field: sortField.value,
            sort_order: sortOrder.value,
        },
        { preserveState: true },
    );
}

// On recharge la page en allant à la page demandée
function goToPage(page: number) {
    router.get(
        'comments',
        {
            page,
            search: search.value,
            sort_field: sortField.value,
            sort_order: sortOrder.value,
        },
        { preserveState: true },
    );
}
</script>
<template>
    <Head title="Liste des commentaires" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">Liste des commentaires ({{ props.comments.total }})</h1>
                <div class="mb-4 flex justify-between">
                    <Link href="/comments/create" class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        Créer un nouveau commentaire
                    </Link>
                    <div class="inline-block">
                        <input
                            type="search"
                            name="comment_search"
                            v-model="search"
                            placeholder="Filtre par mot-clés"
                            class="mr-2 ml-2 inline-block rounded border border-blue-500 px-4 py-2 hover:ring-1 hover:ring-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                        />
                        <div class="relative inline-block">
                            <select
                                name="comment_sort_field"
                                v-model="sortField"
                                class="mr-2 ml-2 inline-block appearance-none rounded border border-blue-500 py-2 pr-8 pl-4 hover:ring-1 hover:ring-blue-500 focus:outline-none"
                            >
                                <option value="created_at">Date</option>
                                <option value="content">Contenu</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative inline-block">
                            <select
                                name="comment_sort_order"
                                v-model="sortOrder"
                                class="mr-2 ml-2 inline-block appearance-none rounded border border-blue-500 py-2 pr-8 pl-4 hover:ring-1 hover:ring-blue-500 focus:outline-none"
                            >
                                <option value="desc">Descendant</option>
                                <option value="asc">Ascendant</option>
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
                <div v-if="!props.comments.total" class="text-gray-500">Aucun commentaire actuellement.</div>
                <ul v-else class="space-y-4">
                    <li v-for="comment in props.comments.data" :key="comment.id" class="rounded-lg border p-4 shadow transition hover:shadow-lg">
                        <Link :href="`/comments/${comment.id}`" class="text-2xl font-semibold text-blue-600 hover:underline">
                            {{ comment.content.slice(0, 100) }}<span v-if="comment.content.length > 100"> ...</span>
                        </Link>
                        <p class="mt-2 text-sm text-gray-500">
                            Article :
                            {{ comment.post.title || 'Inconnu' }}
                        </p>
                        <p class="mt-1 text-sm text-gray-500">
                            Auteur :
                            {{ comment.user.name || 'Inconnu' }}
                        </p>
                        <div class="mt-4 flex space-x-2">
                            <Link :href="`/comments/${comment.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                                Éditer
                            </Link>
                            <button
                                @click="router.delete(`/comments/${comment.id}`, { preserveScroll: true })"
                                class="cursor-pointer rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                            >
                                Supprimer
                            </button>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 flex justify-center">
                    <button
                        v-for="page in props.comments.last_page"
                        :key="page"
                        @click="goToPage(page)"
                        :class="[
                            'mr-2 ml-2 inline-block cursor-pointer rounded border-blue-500 px-4 py-2 hover:ring-1 hover:ring-blue-500',
                            props.comments.current_page == page ? 'border-2 font-bold' : 'border',
                        ]"
                    >
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
