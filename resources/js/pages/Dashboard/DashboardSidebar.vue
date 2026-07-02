<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';

defineProps({
    isSidebarOpen: Boolean,
});

defineEmits(['close-sidebar']);

const logout = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        if (token) {
            await axios.post('/api/logout', {}, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
        }
    } catch (error) {
        console.error(error);
    } finally {
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
    }
};
</script>

<template>
    <!-- Mobile Sidebar Backdrop -->
    <div 
        v-if="isSidebarOpen" 
        @click="$emit('close-sidebar')"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 lg:hidden"
    ></div>

    <!-- Sidebar -->
    <aside 
        :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-white shadow-lg lg:translate-x-0 lg:static lg:inset-auto flex flex-col"
    >
        <div class="flex items-center justify-center h-16 border-b border-gray-200">
            <span class="text-2xl font-bold text-gray-800">HR System</span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- <Link 
                href="/dashboard" 
                class="flex items-center px-4 py-3 rounded-md transition-colors"
                :class="$page.url === '/dashboard' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </Link> -->

            <Link 
                href="/dashboard/employees" 
                class="flex items-center px-4 py-3 rounded-md transition-colors"
                :class="$page.url.startsWith('/dashboard/employees') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Employees
            </Link>
            
            <!-- <Link 
                href="/settings" 
                class="flex items-center px-4 py-3 rounded-md transition-colors"
                :class="$page.url.startsWith('/settings') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </Link> -->
        </nav>
        
        <!-- User Profile / Logout -->
        <div class="p-4 border-t border-gray-200">
            <button @click="logout" class="w-full flex items-center px-4 py-2 text-sm text-red-600 rounded-md hover:bg-red-50 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </div>
    </aside>
</template>
