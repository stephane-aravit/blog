<script setup lang="ts">
import PostForm from '@/components/forms/PostForm.vue';

import { Category, Post, User } from '@/types';
const props = defineProps<{
    post: Post;
    categories: Category[];
    users: User[];
}>();
</script>
<template>
    <PostForm :post="props.post" :categories="props.categories" :users="props.users" />
</template>
