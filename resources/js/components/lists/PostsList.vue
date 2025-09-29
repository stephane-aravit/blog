<script setup lang="ts">
import { Post } from '@/types';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    posts: Post[];
}>();
</script>
<template>
    <div v-if="!props.posts.length" class="text-gray-500">Aucun article actuellement.</div>
    <ul v-else class="space-y-4">
        <li v-for="post in props.posts.slice(0, 3)" :key="post.id" class="mb-4">
            <Link :href="`/posts/${post.id}`" class="text-l font-semibold text-blue-600 hover:underline">
                {{ post.title.slice(0, 50) }}<span v-if="post.title.length > 50"> ...</span>
            </Link>
            <p class="mt-1 text-sm text-gray-500">
                {{ post.user.name }} |
                <span v-if="post.categories.length">
                    <span v-for="(category, index) in post.categories" :key="index">
                        {{ category['name'] }}<span v-if="index < post.categories.length - 1">, </span>
                    </span>
                </span>
                <span v-else> - </span>
            </p>
        </li>
    </ul>
</template>
