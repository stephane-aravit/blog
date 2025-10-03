<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Articles',
        href: '/',
    },
];

import { Comment } from '@/types';
const props = defineProps<{
    comments: Comment[];
}>();
</script>
<template>
    <Head title="Liste des commentaires" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">Liste des commentaires</h1>
                <Link href="/comments/create" class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    Créer un nouveau commentaire
                </Link>
                <div v-if="!props.comments.length" class="text-gray-500">Aucun commentaire actuellement.</div>
                <ul v-else class="space-y-4">
                    <li v-for="comment in props.comments" :key="comment.id" class="rounded-lg border p-4 shadow transition hover:shadow-lg">
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
            </div>
        </div>
    </AppLayout>
</template>
