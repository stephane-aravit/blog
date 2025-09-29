<script setup lang="ts">
import DashboardStats from '@/components/DashboardStats.vue';
import CommentsList from '@/components/lists/CommentsList.vue';
import PostsList from '@/components/lists/PostsList.vue';
import UsersList from '@/components/lists/UsersList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, Comment, Post, User } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tableau de bord',
        href: dashboard().url,
    },
];

const props = defineProps<{
    posts: Post[];
    comments: Comment[];
    users: User[];
    postsRaw: { month: string; total: number }[];
    commentsRaw: { month: string; total: number }[];
    usersRaw: { month: string; total: number }[];
}>();
</script>

<template>
    <Head title="Tableau de bord" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-auto rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <h2 class="mb-2 text-xl font-bold">Derniers articles</h2>
                    <PostsList :posts="posts" />
                </div>
                <div class="relative aspect-video overflow-auto rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <h2 class="mb-2 text-xl font-bold">Derniers commentaires</h2>
                    <CommentsList :comments="comments" />
                </div>
                <div class="relative aspect-video overflow-auto rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <h2 class="mb-2 text-xl font-bold">Derniers utilisateurs</h2>
                    <UsersList :users="users" />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
                <h2 class="mb-2 text-xl font-bold">Statistiques</h2>
                <DashboardStats :posts="postsRaw" :comments="commentsRaw" :users="usersRaw" />
            </div>
        </div>
    </AppLayout>
</template>
