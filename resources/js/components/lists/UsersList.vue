<script setup lang="ts">
import { User } from '@/types';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    users: User[];
}>();

function formatDate(dateStr: string) {
    return new Date(dateStr).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}
</script>
<template>
    <div v-if="!props.users.length" class="text-gray-500">Aucun utilisateur actuellement.</div>
    <ul v-else class="space-y-4">
        <li v-for="user in props.users.slice(0, 3)" :key="user.id" class="mb-4">
            <Link :href="`/users/${user.id}`" class="text-l font-semibold text-blue-600 hover:underline">
                {{ user.name }}
            </Link>
            <p class="mt-1 text-sm text-gray-500">{{ formatDate(user.created_at) }}</p>
        </li>
    </ul>
</template>
