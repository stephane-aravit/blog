<script setup lang="ts">
import CommentForm from '@/components/forms/CommentForm.vue';
import { Post, User } from '@/types';
const props = defineProps<{
    posts: Post[];
    users: User[];
}>();
</script>
<template>
    <CommentForm :posts="props.posts" :users="props.users" />
</template>
