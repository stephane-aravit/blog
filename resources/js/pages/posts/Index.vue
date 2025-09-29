<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Post } from '@/types';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Articles',
        href: '/',
    },
];

const props = defineProps<{
    posts: Post[];
}>();
</script>
<template>
    <Head title="Liste des articles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">Liste des articles</h1>
                <Link href="/posts/create" class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    Créer un nouvel article
                </Link>
                <div v-if="!props.posts.length" class="text-gray-500">Aucun article actuellement.</div>
                <ul v-else class="space-y-4">
                    <li v-for="post in props.posts" :key="post.id" class="rounded-lg border p-4 shadow transition hover:shadow-lg">
                        <Link :href="`/posts/${post.id}`" class="text-2xl font-semibold text-blue-600 hover:underline">
                            {{ post.title }}
                        </Link>
                        <p class="mt-2 break-words whitespace-pre-wrap text-gray-700">
                            {{ post.content.slice(0, 100) }}<span v-if="post.content.length > 100"> ...</span>
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Catégorie(s) :
                            <span v-if="post.categories.length">
                                <span v-for="(category, index) in post.categories" :key="index">
                                    {{ category['name'] }}<span v-if="index < post.categories.length - 1">, </span>
                                </span>
                            </span>
                            <span v-else> - </span>
                        </p>
                        <div class="mt-4 flex space-x-2">
                            <Link :href="`/posts/${post.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                                Éditer
                            </Link>
                            <button
                                @click="Inertia.delete(`/posts/${post.id}`, { preserveScroll: true })"
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
