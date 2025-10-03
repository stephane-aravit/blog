<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
type FlashMessages = {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
};
const flashProps = computed(() => (page.props.flash as FlashMessages) ?? {}); // flashProps est un computed donc en lecture seule donc pas vidable donc aura toujours la meme valeur
const localFlash = ref<FlashMessages>({}); // on utilises donc une copie effaçable pour le cas du rechargement de page (ex: filtre liste)

const type = ref('');
const message = ref('');
const show = ref(false);
const timeout = ref<number>(0);

watch(
    () => flashProps.value,
    (flash) => {
        if (flash) {
            localFlash.value = { ...flash };
            if (localFlash.value.success) {
                type.value = 'success';
                message.value = localFlash.value.success;
            } else if (localFlash.value.error) {
                type.value = 'error';
                message.value = localFlash.value.error;
            } else if (localFlash.value.warning) {
                type.value = 'warning';
                message.value = localFlash.value.warning;
            } else if (localFlash.value.info) {
                type.value = 'info';
                message.value = localFlash.value.info;
            }

            if (message.value) {
                show.value = true;

                if (timeout.value) clearTimeout(timeout.value);

                timeout.value = setTimeout(() => {
                    show.value = false;
                }, 5000);

                localFlash.value = {};
            }
        }
    },
    { immediate: true },
);
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform opacity-0 translate-y-4"
        enter-to-class="transform opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="transform opacity-100 translate-y-0"
        leave-to-class="transform opacity-0 translate-y-4"
    >
        <div
            v-if="show"
            class="pointer-events-auto fixed right-4 bottom-4 z-50 w-full max-w-sm rounded-lg shadow-lg"
            :class="{
                'border border-green-200 bg-green-50 text-green-800': type === 'success',
                'border border-red-200 bg-red-50 text-red-800': type === 'error',
                'border border-blue-200 bg-blue-50 text-blue-800': type === 'info',
                'border border-yellow-200 bg-yellow-50 text-yellow-800': type === 'warning',
            }"
        >
            <div class="flex p-4">
                <div class="flex-shrink-0">
                    <svg
                        class="h-5 w-5"
                        :class="{
                            'text-green-500': type === 'success',
                            'text-red-500': type === 'error',
                            'text-blue-500': type === 'info',
                            'text-yellow-500': type === 'warning',
                        }"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            v-if="type === 'success'"
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                        <path
                            v-if="type === 'error'"
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"
                        />
                        <path
                            v-if="type === 'info'"
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"
                        />
                        <path
                            v-if="type === 'warning'"
                            fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">
                        {{ message }}
                    </p>
                </div>
                <div class="ml-4 flex flex-shrink-0">
                    <button
                        @click="show = false"
                        class="inline-flex rounded-md focus:ring-2 focus:ring-offset-2 focus:outline-none"
                        :class="{
                            'text-green-500 hover:text-green-600 focus:ring-green-500': type === 'success',
                            'text-red-500 hover:text-red-600 focus:ring-red-500': type === 'error',
                            'text-blue-500 hover:text-blue-600 focus:ring-blue-500': type === 'info',
                            'text-yellow-500 hover:text-yellow-600 focus:ring-yellow-500': type === 'warning',
                        }"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>
