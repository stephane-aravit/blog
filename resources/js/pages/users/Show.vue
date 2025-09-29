<script setup lang="ts">
import UserForm from '@/components/forms/UserForm.vue';
import { User } from '@/types';
const props = defineProps<{
    user: User;
}>();
</script>
<template>
    <UserForm :user="props.user" readonly />
</template>
