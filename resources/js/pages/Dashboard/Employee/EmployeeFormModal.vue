<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    employee: Object, // null if creating, object if editing
});

const emit = defineEmits(['close']);

const departments = ['IT', 'Human Resources', 'Finance', 'Marketing', 'Operations', 'Sales'];
const positions = ['Staff', 'Supervisor', 'Manager', 'Analyst', 'Specialist', 'Lead'];

const form = useForm({
    name: '',
    email: '',
    position: '',
    department: '',
    status: 'Active',
});

// Update form when employee prop changes (for edit mode)
watch(() => props.employee, (newEmployee) => {
    if (newEmployee) {
        form.name = newEmployee.name;
        form.email = newEmployee.email;
        form.position = newEmployee.position;
        form.department = newEmployee.department;
        form.status = newEmployee.status;
    } else {
        form.reset();
        form.clearErrors();
    }
}, { immediate: true });

const submit = () => {
    if (props.employee) {
        form.put(`/dashboard/employees/${props.employee.id}`, {
            onSuccess: () => {
                form.reset();
                emit('close');
            },
        });
    } else {
        form.post('/dashboard/employees', {
            onSuccess: () => {
                form.reset();
                emit('close');
            },
        });
    }
};

const close = () => {
    form.reset();
    form.clearErrors();
    emit('close');
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay with blur -->
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="relative z-10 w-full max-w-[400px] bg-white rounded-2xl shadow-2xl transform transition-all overflow-hidden border border-gray-100">
            <form @submit.prevent="submit">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800" id="modal-title">
                        {{ employee ? 'Edit Employee' : 'Add New Employee' }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ employee ? 'Update employee information.' : 'Fill in the details to add a new employee.' }}
                    </p>
                </div>
                
                <!-- Form Body -->
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" v-model="form.name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors py-2 px-3 border text-sm" placeholder="John Doe" required>
                        <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" v-model="form.email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors py-2 px-3 border text-sm" placeholder="john@example.com" required>
                        <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                    </div>
                    
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                        <select id="position" v-model="form.position" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors py-2 px-3 border text-sm bg-white" required>
                            <option value="" disabled>Select Job Title</option>
                            <option v-for="pos in positions" :key="pos" :value="pos">{{ pos }}</option>
                        </select>
                        <div v-if="form.errors.position" class="text-red-500 text-xs mt-1">{{ form.errors.position }}</div>
                    </div>
                    
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="department" v-model="form.department" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors py-2 px-3 border text-sm bg-white" required>
                            <option value="" disabled>Select Department</option>
                            <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
                        </select>
                        <div v-if="form.errors.department" class="text-red-500 text-xs mt-1">{{ form.errors.department }}</div>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" v-model="form.status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors py-2 px-3 border text-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" @click="close" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50 flex items-center">
                        <span v-if="form.processing" class="mr-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                        Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
