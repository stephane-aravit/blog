<script setup lang="ts">
import { Comment } from '@/types';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    comments: Comment[];
}>();
</script>
<template>
    <div v-if="!props.comments.length" class="text-gray-500">Aucun commentaire actuellement.</div>
    <ul v-else class="space-y-4">
        <li v-for="comment in props.comments.slice(0, 3)" :key="comment.id" class="mb-4">
            <Link :href="`/posts/${comment.id}`" class="text-l font-semibold break-words whitespace-pre-wrap text-blue-600 hover:underline">
                {{ comment.content.slice(0, 50) }}<span v-if="comment.content.length > 100"> ...</span>
            </Link>
            <p class="mt-1 text-sm text-gray-500">
                {{ comment.user.name }} | {{ comment.post.title.slice(0, 50) }}<span v-if="comment.post.title.length > 50"> ...</span>
            </p>
        </li>
    </ul>
</template>
