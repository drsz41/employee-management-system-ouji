<script setup>
import { ref, onMounted } from 'vue';
import DashboardSidebar from './DashboardSidebar.vue';
import Toast from '../../components/Toast.vue';
import axios from 'axios';

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

onMounted(() => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login';
    } else {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        // Also attach to window.axios just in case Inertia uses it
        window.axios = axios;
    }
});
</script>

<template>
    <div class="flex h-screen bg-gray-100 font-sans">
        <Toast />
        
        <!-- Sidebar Component -->
        <DashboardSidebar 
            :isSidebarOpen="isSidebarOpen" 
            @close-sidebar="isSidebarOpen = false" 
        />

        <!-- Main Content (Sebelah Kanan) -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Header area (Mobile toggle & Breadcrumb) -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 lg:hidden">
                <button @click="toggleSidebar" class="text-gray-500 focus:outline-none focus:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="text-lg font-semibold text-gray-800">
                    <slot name="header">Dashboard</slot>
                </span>
            </header>

            <!-- Page Content Area -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <slot />
            </div>
        </main>
    </div>
</template>
