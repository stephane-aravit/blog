<script setup lang="ts">
import UserForm from '@/components/forms/UserForm.vue';
const props = defineProps<{
    roles: string[];
}>();
</script>
<template>
    <UserForm :roles="props.roles" />
</template>
