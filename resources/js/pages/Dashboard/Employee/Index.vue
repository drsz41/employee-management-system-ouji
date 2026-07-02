<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DashboardLayout from '../DashboardLayout.vue';
import EmployeeFormModal from './EmployeeFormModal.vue';
import EmployeeFilter from './EmployeeFilter.vue';

// Props yang diterima dari Laravel Controller
const props = defineProps({
    employees: Object, // Objek paginasi dari Laravel
    filters: Object,   // Objek filter untuk search
});

const handleFilter = (filters) => {
    router.get('/dashboard/employees', filters, {
        preserveState: true,
        replace: true
    });
};

// Form Modal State
const isFormModalOpen = ref(false);
const editingEmployee = ref(null);

const openCreateModal = () => {
    editingEmployee.value = null;
    isFormModalOpen.value = true;
};

const openEditModal = (employee) => {
    editingEmployee.value = employee;
    isFormModalOpen.value = true;
};

const closeFormModal = () => {
    isFormModalOpen.value = false;
    editingEmployee.value = null;
};

// Delete Modal State
const isDeleteModalOpen = ref(false);
const employeeToDelete = ref(null);

const confirmDelete = (employee) => {
    employeeToDelete.value = employee;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (employeeToDelete.value) {
        router.delete(`/dashboard/employees/${employeeToDelete.value.id}`, {
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                employeeToDelete.value = null;
            }
        });
    }
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    employeeToDelete.value = null;
};
</script>

<template>
    <Head title="Employee Dashboard" />

    <DashboardLayout>
        <template #header>Employees</template>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <h1 class="text-2xl font-semibold text-gray-800">Employee Directory</h1>
            
            <button @click="openCreateModal" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors shadow-sm whitespace-nowrap font-medium text-sm">
                + Add Employee
            </button>
        </div>

        <!-- Filter Component Centered Above Table -->
        <EmployeeFilter :initialFilters="filters" @filter="handleFilter" />

        <!-- Table Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Jika data kosong -->
                                <tr v-if="!employees || employees.data.length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No employees found.
                                    </td>
                                </tr>
                                
                                <!-- Looping Data Pegawai -->
                                <tr v-for="employee in employees?.data" :key="employee.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <!-- Avatar placeholder -->
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                                    {{ employee.name.charAt(0) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ employee.name }}</div>
                                                <div class="text-sm text-gray-500">{{ employee.email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ employee.position }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ employee.department }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="employee.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        >
                                            {{ employee.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="openEditModal(employee)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        <button @click="confirmDelete(employee)" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Layout (opsional) -->
                    <div v-if="employees && employees.links" class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ employees.from }}</span> to <span class="font-medium">{{ employees.to }}</span> of <span class="font-medium">{{ employees.total }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <template v-for="(link, key) in employees.links" :key="key">
                                        <Link
                                            v-if="link.url"
                                            :href="link.url"
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                            :class="link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                                            >
                                            <span v-html="link.label"></span>
                                        </Link>
                                        <span v-else class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                            <span v-html="link.label"></span>
                                        </span>
                                    </template>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Employee Form Modal -->
        <EmployeeFormModal 
            :show="isFormModalOpen" 
            :employee="editingEmployee" 
            @close="closeFormModal" 
        />

        <!-- Custom Delete Confirmation Modal -->
        <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all text-center border border-gray-100">
                <div class="px-6 pt-8 pb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Employee</h3>
                    <p class="text-gray-500 text-sm">
                        Are you sure you want to delete <span class="font-semibold text-gray-800">{{ employeeToDelete?.name }}</span>? This action cannot be undone.
                    </p>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex justify-center space-x-3">
                    <button @click="closeDeleteModal" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors">
                        Cancel
                    </button>
                    <button @click="executeDelete" class="px-5 py-2.5 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>