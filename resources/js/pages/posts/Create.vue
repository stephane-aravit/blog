<script setup lang="ts">
import PostForm from '@/components/forms/PostForm.vue';
import { Category, User } from '@/types';
const props = defineProps<{
    categories: Category[];
    users: User[];
}>();
</script>
<template>
    <PostForm :categories="props.categories" :users="props.users" />
</template>
