<script setup lang="ts">
import type { ChartData } from 'chart.js';
import { CategoryScale, Chart as ChartJS, Legend, LinearScale, LineElement, PointElement, Title, Tooltip } from 'chart.js';
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale);

const props = defineProps<{
    posts: { month: string; total: number }[];
    comments: { month: string; total: number }[];
    users: { month: string; total: number }[];
}>();

const allLabels = Array.from(
    new Set([...props.posts.map((a) => a.month), ...props.comments.map((c) => c.month), ...props.users.map((u) => u.month)]),
).sort();

function mapData(labels: string[], source: { month: string; total: number }[]) {
    const map = new Map(source.map((s) => [s.month, s.total]));
    return labels.map((label) => map.get(label) ?? 0);
}

const chartOptions = {
    responsive: true,
};

const chartData = computed<ChartData<'line'>>(() => ({
    labels: allLabels,
    datasets: [
        {
            label: 'Articles',
            data: mapData(allLabels, props.posts),
            borderColor: 'blue',
            backgroundColor: 'blue',
        },
        {
            label: 'Commentaires',
            data: mapData(allLabels, props.comments),
            borderColor: 'green',
            backgroundColor: 'green',
        },
        {
            label: 'Utilisateurs',
            data: mapData(allLabels, props.users),
            borderColor: 'red',
            backgroundColor: 'red',
        },
    ],
}));
</script>
<template>
    <Line id="my-chart-id" :options="chartOptions" :data="chartData" />
</template>
<style scoped>
.chart-container {
    width: 100%;
    height: 400px;
}
</style>
