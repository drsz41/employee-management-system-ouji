<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    initialFilters: {
        type: Object,
        default: () => ({ position: '', department: '', status: '' })
    }
});

const emit = defineEmits(['filter']);

const departments = ['IT', 'Human Resources', 'Finance', 'Marketing', 'Operations', 'Sales'];
const positions = ['Staff', 'Supervisor', 'Manager', 'Analyst', 'Specialist', 'Lead'];
const statuses = ['Active', 'Inactive', 'On Leave'];

const filterPosition = ref(props.initialFilters.position || '');
const filterDepartment = ref(props.initialFilters.department || '');
const filterStatus = ref(props.initialFilters.status || '');

watch(() => props.initialFilters, (newVal) => {
    filterPosition.value = newVal.position || '';
    filterDepartment.value = newVal.department || '';
    filterStatus.value = newVal.status || '';
}, { deep: true });

const applyFilters = () => {
    emit('filter', {
        position: filterPosition.value,
        department: filterDepartment.value,
        status: filterStatus.value
    });
};

const exportCsv = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        const query = new URLSearchParams({
            position: filterPosition.value,
            department: filterDepartment.value,
            status: filterStatus.value
        }).toString();
        
        const response = await axios.get(`/dashboard/employees/export?${query}`, {
            headers: {
                Authorization: `Bearer ${token}`
            },
            responseType: 'blob'
        });
        
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        const filename = response.headers['content-disposition'] 
            ? response.headers['content-disposition'].split('filename=')[1].replace(/"/g, '')
            : 'employees_export.csv';
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error(error);
        alert('Failed to export CSV. Please ensure you are authenticated.');
    }
};
</script>

<template>
    <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4 mb-6">
        <select v-model="filterPosition" class="w-full sm:w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-white text-gray-700">
            <option value="">All Titles</option>
            <option v-for="pos in positions" :key="pos" :value="pos">{{ pos }}</option>
        </select>
        
        <select v-model="filterDepartment" class="w-full sm:w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-white text-gray-700">
            <option value="">All Departments</option>
            <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
        </select>
        
        <select v-model="filterStatus" class="w-full sm:w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-white text-gray-700">
            <option value="">All Statuses</option>
            <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
        </select>

        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <button @click="applyFilters" class="w-full sm:w-auto px-6 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-lg hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors shadow-sm whitespace-nowrap font-medium text-sm flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Apply Filters
            </button>
            <button @click="exportCsv" class="w-full sm:w-auto px-4 py-2 bg-green-50 border border-green-200 text-green-700 rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors shadow-sm whitespace-nowrap font-medium text-sm flex items-center justify-center" title="Export to CSV">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export CSV
            </button>
        </div>
    </div>
</template>
