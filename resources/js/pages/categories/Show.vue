<script setup lang="ts">
import CategoryForm from '@/components/forms/CategoryForm.vue';
import { Category } from '@/types';
const props = defineProps<{
    category: Category;
}>();
</script>
<template>
    <CategoryForm :category="props.category" readonly />
</template>
