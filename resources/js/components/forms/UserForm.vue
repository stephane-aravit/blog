<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Utilisateurs',
        href: '/',
    },
];

import type { User } from '@/types';
const props = defineProps<{
    user?: User;
    roles?: string[];
    readonly?: boolean;
}>();

const form = useForm<Pick<User, 'name' | 'email' | 'password' | 'password_confirmation' | 'role'>>({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    password: '',
    password_confirmation: '',
    role: props.user?.role ?? '',
});

const submit = () => {
    if (props.readonly) {
        return;
    } else if (props.user) {
        form.put(`/users/${props.user.id}`, { preserveScroll: true });
    } else {
        form.post('/users', { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="props.user ? 'Édition d\'un utilisateur' : 'Création d\'un utilisateur'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h1 class="mb-4 text-3xl font-bold">{{ props.user ? "Édition d'un utilisateur" : "Création d'un utilisateur" }}</h1>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="user-name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input
                            id="user-name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required
                            :readonly="props.readonly"
                        />
                        <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
                    </div>
                    <div>
                        <label for="user-email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            id="user-email"
                            v-model="form.email"
                            type="text"
                            class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required
                            :readonly="props.readonly"
                        />
                        <div v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</div>
                    </div>
                    <div>
                        <label for="user-password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input
                            id="user-password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            :required="!props.user"
                            :readonly="props.readonly"
                        />
                        <div v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</div>
                    </div>
                    <div>
                        <label for="user-password-confirmation" class="block text-sm font-medium text-gray-700">Confirmation mot de passe</label>
                        <input
                            id="user-password-confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            :required="!props.user"
                            :readonly="props.readonly"
                        />
                        <div v-if="form.errors.password_confirmation" class="text-sm text-red-500">{{ form.errors.password_confirmation }}</div>
                    </div>
                    <div>
                        <label for="user-role" class="block text-sm font-medium text-gray-700">Rôle</label>
                        <select
                            id="user-role"
                            v-model="form.role"
                            :class="[
                                'mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
                                props.readonly ? 'cursor-not-allowed' : '',
                            ]"
                            required
                            :disabled="props.readonly"
                        >
                            <option v-for="role in props.roles" :key="role" :value="role">
                                {{ role }}
                            </option>
                        </select>
                        <div v-if="form.errors.role" class="text-sm text-red-500">{{ form.errors.role }}</div>
                    </div>
                    <div v-if="props.readonly && props.user" class="mt-4 flex space-x-2">
                        <Link :href="`/users/${props.user.id}/edit`" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                            Éditer
                        </Link>
                        <button
                            @click="router.delete(`/users/${props.user.id}`, { preserveScroll: true })"
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
                            {{ props.user ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
