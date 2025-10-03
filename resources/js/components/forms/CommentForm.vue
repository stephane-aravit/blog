<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Commentaires',
        href: '/',
    },
];

import type { Comment, Post, User } from '@/types';
const props = defineProps<{
    comment?: Comment;
    posts: Post[];
    users: User[];
    readonly?: boolean;
}>();

const form = useForm<Partial<Pick<Comment, 'content' | 'post_id' | 'user_id'>>>({
    content: props.comment ? props.comment.content : '',
    post_id: props.comment?.post_id ?? undefined,
    user_id: props.comment?.user_id ?? undefined,
});

const submit = () => {
    if (props.readonly) {
        return;
    } else if (props.comment) {
        form.put(`/comments/${props.comment.id}`, { preserveScroll: true });
    } else {
        form.post('/comments', { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="props.comment ? 'Édition d\'un commentaire' : 'Création d\'un commentaire'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">{{ props.comment ? "Édition d'un commentaire" : "Création d'un commentaire" }}</h1>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Contenu</label>
                        <textarea
                            id="content"
                            v-model="form.content"
                            rows="5"
                            :class="[
                                'mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
                                props.readonly ? 'cursor-not-allowed' : '',
                            ]"
                            required
                            :readonly="props.readonly"
                        ></textarea>
                        <div v-if="form.errors.content" class="text-sm text-red-500">{{ form.errors.content }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Article</label>
                        <select
                            v-model="form.post_id"
                            :class="[
                                'mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
                                props.readonly ? 'cursor-not-allowed' : '',
                            ]"
                            required
                            :disabled="props.readonly"
                        >
                            <option v-for="post in props.posts" :key="post.id" :value="post.id">
                                {{ post.title }}
                            </option>
                        </select>
                        <div v-if="form.errors.post_id" class="text-sm text-red-500">{{ form.errors.post_id }}</div>
                    </div>
                    <div v-if="page.props.auth.user.role === 'admin'">
                        <label class="block text-sm font-medium text-gray-700">Utilisateur</label>
                        <select
                            v-model="form.user_id"
                            :class="[
                                'mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
                                props.readonly ? 'cursor-not-allowed' : '',
                            ]"
                            required
                            :disabled="props.readonly"
                        >
                            <option v-for="user in props.users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                        </select>
                        <div v-if="form.errors.user_id" class="text-sm text-red-500">{{ form.errors.user_id }}</div>
                    </div>
                    <div v-if="props.readonly && props.comment" class="mt-4 flex space-x-2">
                        <Link :href="`/comments/${props.comment.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                            Éditer
                        </Link>
                        <button
                            @click="router.delete(`/comments/${props.comment.id}`, { preserveScroll: true })"
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
                            {{ props.comment ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
