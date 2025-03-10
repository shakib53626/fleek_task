<script setup>
import { onMounted, ref, watch } from "vue";
import { useCategoryStore } from "../stores";

const category = useCategoryStore();

const isOpen        = ref(false);
const nameError     = ref(null);
const imageError    = ref('');
const imagePreview  = ref(null);
const loading       = ref(false);
const deleteLoading = ref(false);
const form          = ref({
    id    : '',
    name  : '',
    status: 'Active',
    image : null
});

const openModal = (data) => {
    form.value.id       = data?.id ? data?.id : '',
    form.value.name     = data?.name ? data?.name : '';
    form.value.status   = data?.status ? data?.status : 'Active';
    isOpen.value        = true;
}


const validateName = () => {
    if (!form.value.name) {
        nameError.value = "Name is required.";
    }else {
        nameError.value = null;
    }
};

// ✅ Watcher: Real-time validation
watch(() => form.value.name, validateName);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (!file.type.startsWith('image/')) {
            imageError.value = 'Only image files are allowed';
            form.value.image = null;
            imagePreview.value = null;
        } else {
            imageError.value = '';
            form.value.image = file;
            imagePreview.value = URL.createObjectURL(file);
        }
    }
};

const handleSubmit = async() => {

    validateName();
    loading.value = true;

    let api = '';
    if(form.value?.id){
        api = `/categories/${form.value?.id}`
    }else{
        api = 'categories'
    }

    const res = await category.insert(api, form.value);
    if(res?.success){
        isOpen.value = false;
        category.getData();
        loading.value = false;

    }else{

        nameError.value = res?.errors?.name?.[0] || null;
        loading.value = false;
    }
}

const handleDelete = async(data) =>{
    deleteLoading.value = true;
    const res = await category.destroy(data);
    if(res?.success){
        category.getData();
        deleteLoading.value = false;
    }else{
        deleteLoading.value = false;
    }
}

onMounted(() => {
    category.getData();
})
</script>

<template>
    <div class="overflow-x-auto mt-2">
        <div class="flex justify-between items-center mb-3">
            <h6 class="text-lg font-semibold">All Products List</h6>
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
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="text-gray-600 text-sm divide-y divide-gray-200">

                <tr class="hover:bg-gray-50" v-for="(data, i) in category?.categories" :key="i">
                    <td class="py-4 px-6">{{ i+1 }}</td>
                    <td class="py-4 px-6">{{ data?.name }}</td>
                    <td class="py-4 px-6">{{ data?.status }}</td>
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

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select v-model="form.status"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                        <!-- Image Upload -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Upload Image</label>
                        <div class="flex items-center space-x-4">
                            <input type="file" @change="handleImageUpload" accept="image/*"
                                class="hidden" id="imageUpload">
                            <label for="imageUpload"
                                class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
                                Choose File
                            </label>
                            <span v-if="form.image" class="text-gray-600">{{ form.image.name }}</span>
                        </div>
                        <span v-if="imageError" class="text-red-500 text-sm">{{ imageError }}</span>

                        <!-- Image Preview -->
                        <div v-if="imagePreview" class="mt-4">
                            <img :src="imagePreview" alt="Uploaded Image" class="w-24 h-24 rounded-lg shadow">
                        </div>
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
