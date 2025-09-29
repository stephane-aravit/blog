<script setup lang="ts">
import CommentForm from '@/components/forms/CommentForm.vue';
import { Comment, Post, User } from '@/types';
const props = defineProps<{
    comment: Comment;
    posts: Post[];
    users: User[];
}>();
</script>
<template>
    <CommentForm :comment="props.comment" :posts="props.posts" :users="props.users" />
</template>
