<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

import { Category, Post } from '@/types';

const props = defineProps<{
    posts: Post[];
    categories: Category[];
}>();
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Tableau de bord
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Connexion
                    </Link>
                    <Link
                        :href="register()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Inscription
                    </Link>
                </template>
            </nav>
        </header>
        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <div class="w-full max-w-[335px] lg:max-w-4xl">
                <h1 class="mb-6 text-4xl leading-tight font-extrabold tracking-tighter lg:text-5xl">Bienvenue sur le blog</h1>
                <p class="mb-6 text-lg leading-relaxed text-[#52534e] dark:text-[#A3A29E]">
                    C'est une application simple construite avec Laravel et Inertia. Vous pouvez gérer les articles, les catégories et les
                    commentaires. Veuillez vous connecter ou vous inscrire pour commencer.
                </p>
                <Link
                    v-if="!$page.props.auth.user"
                    :href="register()"
                    class="inline-block rounded bg-[#191400] px-6 py-3 text-lg font-semibold text-white hover:bg-[#2c2a00] dark:bg-[#EDEDEC] dark:text-[#1b1b18] dark:hover:bg-[#c4c3c0]"
                >
                    Commencer
                </Link>
            </div>
        </div>
        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="mb-4 text-xl font-bold">Derniers articles créés</h2>
                <div v-if="!props.posts.length" class="text-gray-500">Aucun article actuellement.</div>
                <ul v-else class="space-y-4">
                    <li v-for="post in props.posts" :key="post.id" class="rounded-lg border p-4 shadow transition hover:shadow-lg">
                        <p class="text-2xl font-semibold text-blue-600">
                            {{ post.title }}
                        </p>
                        <p class="mt-2 break-words whitespace-pre-wrap text-gray-700">
                            {{ post.content.slice(0, 250) }}<span v-if="post.content.length > 250"> ...</span>
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Catégories:
                            <span v-if="post.categories.length">
                                <span v-for="(category, index) in post.categories" :key="category.valueOf">
                                    {{ category.name }}<span v-if="index < post.categories.length - 1">, </span>
                                </span>
                            </span>
                            <span v-else>Aucune catégorie</span>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
