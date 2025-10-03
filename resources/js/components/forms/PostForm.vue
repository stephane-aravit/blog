<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Articles',
        href: '/',
    },
];

import type { Category, Post, User } from '@/types';
const props = defineProps<{
    post?: Post;
    categories: Category[];
    users: User[];
    readonly?: boolean;
}>();

const form = useForm<Pick<Post, 'title' | 'content' | 'categories' | 'user_id'>>({
    title: props.post?.title ?? '',
    content: props.post?.content ?? '',
    categories: props.post?.categories.map((cat: any) => (typeof cat === 'number' ? cat : cat.id)) ?? [],
    user_id: props.post?.user_id ?? null,
});

const submit = () => {
    if (props.readonly) {
        return;
    } else if (props.post) {
        form.put(`/posts/${props.post.id}`, { preserveScroll: true });
    } else {
        form.post('/posts', { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="props.readonly ? 'Affichage d\'un article' : props.post ? 'Édition d\'un article' : 'Création d\'un article'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">
                    {{ props.readonly ? "Affichage d'un article" : props.post ? "Édition d'un article" : "Création d'un article" }}
                </h1>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            :class="[
                                'mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
                                props.readonly ? 'hover:cursor-not-allowed' : '',
                            ]"
                            :required="!props.readonly"
                            :readonly="props.readonly"
                        />
                        <div v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</div>
                    </div>
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Contenu</label>
                        <textarea
                            id="content"
                            v-model="form.content"
                            rows="5"
                            :class="[
                                'mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
                                props.readonly ? 'hover:cursor-not-allowed' : '',
                            ]"
                            :required="!props.readonly"
                            :readonly="props.readonly"
                        ></textarea>
                        <div v-if="form.errors.content" class="text-sm text-red-500">{{ form.errors.content }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catégories</label>
                        <div v-for="category in props.categories" :key="category.id" class="flex items-center space-x-2">
                            <input
                                :id="`category-${category.id}`"
                                type="checkbox"
                                :value="category.id"
                                v-model="form.categories"
                                :class="[
                                    'h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500',
                                    props.readonly ? 'hover:cursor-not-allowed' : '',
                                ]"
                                :disabled="props.readonly"
                            />
                            <label :for="`category-${category.id}`">{{ category.name }}</label>
                        </div>
                        <div v-if="form.errors.categories" class="text-sm text-red-500">{{ form.errors.categories }}</div>
                    </div>
                    <div v-if="page.props.auth.user.role === 'admin'">
                        <label class="block text-sm font-medium text-gray-700">Utilisateur</label>
                        <select
                            v-model="form.user_id"
                            :class="[
                                'mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
                                props.readonly ? 'hover:cursor-not-allowed' : '',
                            ]"
                            :required="!props.readonly"
                            :disabled="props.readonly"
                        >
                            <option v-for="user in props.users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                        </select>
                        <div v-if="form.errors.user_id" class="text-sm text-red-500">{{ form.errors.user_id }}</div>
                    </div>
                    <div v-if="props.readonly && props.post" class="mt-4 flex space-x-2">
                        <Link :href="`/posts/${props.post.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                            Éditer
                        </Link>
                        <button
                            @click="router.delete(`/posts/${props.post.id}`, { preserveScroll: true })"
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
                            {{ props.post ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
