<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';

// Form state management menggunakan Inertia
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = async () => {
    try {
        const response = await axios.post('/api/login', {
            email: form.email,
            password: form.password,
        });
        
        // Simpan token ke localStorage untuk API Auth
        localStorage.setItem('auth_token', response.data.access_token);
        
        // Set default header untuk axios
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;
        
        // Redirect manual (bukan SPA route)
        window.location.href = '/dashboard/employees';
    } catch (error) {
        if (error.response?.status === 401) {
            form.setError('email', 'Invalid credentials');
        } else {
            console.error(error);
        }
        form.reset('password');
    }
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <h2 class="text-3xl font-bold text-gray-900 text-center">Employee Management System Ouji</h2>
            </Link>
        </div>

        <div v-if="$page.props.flash?.alert" class="w-full sm:max-w-md mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-center font-medium">
            {{ $page.props.flash.alert }}
        </div>
        <div v-if="form.errors.email"  class="w-full sm:max-w-md mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-center font-medium">
                        {{ form.errors.email }}
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form @submit.prevent="submit">
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        class="mt-1 px-2 h-10 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <label class="text-xs">test@example.com</label>
                    
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        class="mt-1 px-2 h-10 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <label class="text-xs">password</label>
                    <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- <div class="block mt-4">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            v-model="form.remember"
                        />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div> -->

                <div class="flex items-center justify-end mt-4">
                    <!-- <Link
                        href="/forgot-password"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Forgot your password?
                    </Link> -->

                    <button
                        type="submit"
                        class="w-full text-center  px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>