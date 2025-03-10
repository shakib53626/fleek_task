<script setup>
import { onMounted, ref, watch } from "vue";
import { useUserStore } from "../stores";

const user = useUserStore();

const isOpen        = ref(false);
const emailError    = ref(null);
const nameError     = ref(null);
const roleError     = ref(null);
const passwordError = ref(null);
const loading       = ref(false);
const deleteLoading = ref(false);
const form          = ref({
    id      : '',
    email   : '',
    name    : '',
    role    : '',
    password: ''
});

const openModal = (data) => {
    form.value.id       = data?.id ? data?.id : '',
    form.value.name     = data?.name ? data?.name : '';
    form.value.email    = data?.email ? data?.email : '';
    form.value.role     = data?.role ? data?.role : '';
    form.value.password = '';
    isOpen.value        = true;
}

const validateEmail = () => {
    if (!form.value.email) {
        emailError.value = "Email is required.";
    } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
        emailError.value = "Invalid email format.";
    } else {
        emailError.value = null;
    }
};
const validateName = () => {
    if (!form.value.name) {
        nameError.value = "Name is required.";
    }else {
        nameError.value = null;
    }
};
const validateRole = () => {
    if (!form.value.role) {
        console.log(form.value.role);

        roleError.value = "Role is required.";
    }else {
        roleError.value = null;
    }
};
const validatePassword = () => {
    if(form.value.id){
        passwordError.value = null
    } else if (!form.value.password) {
        passwordError.value = "Password is required.";
    } else if (form.value.password.length < 6) {
        passwordError.value = "Password must be at least 6 characters.";
    } else {
        passwordError.value = null;
    }
};

// ✅ Watcher: Real-time validation
watch(() => form.value.email, validateEmail);
watch(() => form.value.name, validateName);
watch(() => form.value.role, validateRole);
watch(() => form.value.password, validatePassword);

const handleSubmit = async() => {
    validateEmail();
    validateName();
    validateRole();
    validatePassword();
    loading.value = true;

    let api = '';
    if(form.value?.id){
        api = `/users/${form.value?.id}`
    }else{
        api = 'users'
    }

    const res = await user.insert(api, form.value);
    console.log(res);

    if(res?.success){
        isOpen.value = false;
        user.getData();
        loading.value = false;

    }else{

        emailError.value = res?.errors?.email?.[0] || null;
        nameError.value = res?.errors?.name?.[0] || null;
        roleError.value = res?.errors?.role?.[0] || null;
        passwordError.value = res?.errors?.password?.[0] || null;
        loading.value = false;
    }
}

const handleDelete = async(data) =>{
    deleteLoading.value = true;
    const res = await user.destroy(data);
    if(res?.success){
        user.getData();
        deleteLoading.value = false;
    }else{
        deleteLoading.value = false;
    }
}

onMounted(() => {
    user.getData();
})
</script>

<template>
    <div class="overflow-x-auto mt-2">
        <div class="flex justify-between items-center mb-3">
            <h6 class="text-lg font-semibold">All Users List</h6>

            <div>
                <button class="bg-gray-800 text-white px-3 py-1 mx-1.5 rounded text-sm cursor-pointer hover:bg-blue-600" @click="openModal('')">Add</button>
                <button class="bg-gray-800 text-white px-3 py-1 rounded text-sm cursor-pointer hover:bg-blue-600">Back</button>
            </div>
        </div>

        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">

            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="py-3 px-6 text-left">#</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Role</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="text-gray-600 text-sm divide-y divide-gray-200">

                <tr class="hover:bg-gray-50" v-for="(data, i) in user?.users" :key="i">
                    <td class="py-4 px-6">{{ i+1 }}</td>
                    <td class="py-4 px-6">{{ data?.name }}</td>
                    <td class="py-4 px-6">{{ data?.email }}</td>
                    <td class="py-4 px-6">{{ data?.role }}</td>

                    <td class="py-4 px-6 flex justify-center space-x-2">

                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs cursor-pointer hover:bg-blue-600" @click="openModal(data)" >Edit</button>
                        <button class="bg-red-500 text-white px-3 py-1 rounded text-xs cursor-pointer hover:bg-red-600" @click="handleDelete(data)">
                            <span v-if="deleteLoading">Loading...</span>
                            <span v-else>Delete</span>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- Modal -->
        <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center bg-gray-900/50 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">

                <form @submit.prevent="handleSubmit">
                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input v-model="form.name" type="text" placeholder="Enter your name"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="{
                                'border-green-500 focus:ring-2 focus:ring-green-400': form.name && !nameError,
                                'border-red-500 focus:ring-2 focus:ring-red-400': nameError
                            }"
                            @blur="validateName"
                        >
                        <span v-if="nameError" class="text-red-500 text-sm">{{ nameError }}</span>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input v-model="form.email"
                            type="email"
                            placeholder="Enter your email"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="{
                                'border-green-500 focus:ring-2 focus:ring-green-400': form.email && !emailError,
                                'border-red-500 focus:ring-2 focus:ring-red-400': emailError
                            }"
                            @blur="validateEmail"
                        >
                        <span v-if="emailError" class="text-red-500 text-sm">{{ emailError }}</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Choose a fruit</label>
                        <select v-model="form.role"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="{
                                'border-green-500 focus:ring-2 focus:ring-green-400': form.role && !roleError,
                                'border-red-500 focus:ring-2 focus:ring-red-400': roleError
                            }"
                            @blur="validateRole"
                        >
                          <option disabled value="">Select a Role</option>
                          <option value="admin">Admin</option>
                          <option value="user">User</option>
                        </select>
                        <span v-if="roleError" class="text-red-500 text-sm">{{ roleError }}</span>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input v-model="form.password"
                            type="password"
                            placeholder="Enter your Password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="{
                                'border-green-500 focus:ring-2 focus:ring-green-400': form.password && !passwordError,
                                'border-red-500 focus:ring-2 focus:ring-red-400': passwordError
                            }"
                            @blur="validatePassword"
                        >
                        <span v-if="passwordError" class="text-red-500 text-sm">{{ passwordError }}</span>
                    </div>

                </form>

                <div class="flex justify-end space-x-2">
                    <button @click="isOpen = false" class="px-4 py-2 cursor-pointer bg-gray-400 text-white rounded">Cancel</button>
                    <button class="px-4 py-2 bg-blue-500 cursor-pointer text-white rounded" @click="handleSubmit" v-if="loading">Loading ...</button>
                    <button class="px-4 py-2 bg-blue-500 cursor-pointer text-white rounded" @click="handleSubmit" v-else>Confirm</button>
                </div>
            </div>
        </div>

    </div>

</template>

<style scoped>

</style>
