<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { User } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Utilisateurs',
        href: '/',
    },
];

const props = defineProps<{
    users: User[];
}>();
</script>
<template>
    <Head title="Liste des utilisateurs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">Liste des utilisateurs</h1>
                <Link href="/users/create" class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    Créer une nouvel utilisateur
                </Link>
                <div v-if="!props.users.length" class="text-gray-500">Aucune utilisateur actuellement.</div>
                <ul v-else class="space-y-4">
                    <li v-for="user in props.users" :key="user.id" class="rounded-lg border p-4 shadow transition hover:shadow-lg">
                        <Link :href="`/users/${user.id}`" class="text-2xl font-semibold text-blue-600 hover:underline">
                            {{ user.name }}
                        </Link>
                        <p class="mt-2 break-words whitespace-pre-wrap text-gray-700">
                            {{ user.email }}
                        </p>
                        <p class="mt-2 break-words whitespace-pre-wrap text-gray-700">
                            {{ user.role }}
                        </p>
                        <div class="mt-4 flex space-x-2">
                            <Link :href="`/users/${user.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                                Éditer
                            </Link>
                            <button
                                @click="router.delete(`/users/${user.id}`, { preserveScroll: true })"
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
