<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const showToast = ref(false);
const toastMessage = ref('');
let toastTimeout = null;

watch(() => page.props.flash?.success, (newVal) => {
    if (newVal) {
        toastMessage.value = newVal;
        showToast.value = true;
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            showToast.value = false;
        }, 3000);
    }
}, { immediate: true });
</script>

<template>
    <transition 
        enter-active-class="transform ease-out duration-300 transition" 
        enter-from-class="-translate-y-4 opacity-0 scale-95" 
        enter-to-class="translate-y-0 opacity-100 scale-100" 
        leave-active-class="transition ease-in duration-200" 
        leave-from-class="opacity-100 scale-100" 
        leave-to-class="opacity-0 scale-95"
    >
        <div v-if="showToast" class="fixed top-8 left-1/2 transform -translate-x-1/2 z-[100] min-w-[320px] max-w-lg bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center justify-center space-x-3 border border-gray-700">
            <div class="bg-green-500/20 text-green-400 rounded-full p-1 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="font-semibold text-base text-center tracking-wide">{{ toastMessage }}</span>
        </div>
    </transition>
</template>
