<script setup>
import { onMounted, ref, watch } from "vue";
import { useCategoryStore, useProductStore } from "../stores";

const category = useCategoryStore();
const product  = useProductStore();

const isOpen        = ref(false);
const nameError     = ref(null);
const imageError    = ref('');
const imagePreview  = ref(null);
const loading       = ref(false);
const deleteLoading = ref(false);
const categories    = ref([]);
const categoryData  = ref('');
const searchKey     = ref('');
const paginateSize  = ref('');
const form          = ref({
    id    : '',
    name  : '',
    status: 'Active',
    image : null
});

const openModal = (data) => {
    form.value.id     = data?.id ? data?.id : '',
    form.value.name   = data?.name ? data?.name : '';
    form.value.status = data?.status ? data?.status : 'Active';
    categories.value  = data?.categories;
    isOpen.value      = true;
}

const validateName = () => {
    if (!form.value.name) {
        nameError.value = "Name is required.";
    }else {
        nameError.value = null;
    }
};

const allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/gif", "image/svg+xml"];

const handleImageUpload = (event) => {
    const file = event.target.files[0];

    if (file) {
        if (!allowedTypes.includes(file.type)) {
            imageError.value   = "Only JPEG, PNG, JPG, GIF, and SVG files are allowed.";
            form.value.image   = null;
            imagePreview.value = null;
        } else {
            imageError.value   = "";
            form.value.image   = file;
            imagePreview.value = URL.createObjectURL(file);
        }
    }
};

const handleSubmit = async() => {

    validateName();
        loading.value = true;
    let api           = '';

    let formData = new FormData();
    formData.append('name', form.value.name );
    formData.append('status', form.value.status );
    formData.append('image', form.value.image );
    // formData.append('category_ids[]', categories.value.map(i=> i.id) );

    let i = 0;
    for (const catId of categories.value) {
        formData.append(`category_ids[${i}]`, catId.id);
        i++;
    }

    if(form.value?.id){
        api = `/products/${form.value?.id}`
        formData.append('_method', 'put');
    }else{
        api = 'products'
    }

    const res = await product.insert(api, formData);
    if(res?.success){
        isOpen.value = false;
        product.getData();
        loading.value = false;

    }else{

        nameError.value = res?.errors?.name?.[0] || null;
        loading.value = false;
    }
}

const handleDelete = async(data) =>{

    deleteLoading.value = true;
    const res = await product.destroy(data);

    if(res?.success){
        product.getData();
        deleteLoading.value = false;
    }else{
        deleteLoading.value = false;
    }
}

const selectCategory = () =>{

    if(categories.value.includes(categoryData.value)){
        categories.value.splice(categories.value?.findIndex((i)=> i.id == categoryData.value.id), 1);
    }else{
        categories.value.push(categoryData.value);
    }
}

const searchProduct = async(page=1) =>{
    const res = await product.getData(searchKey.value, page, paginateSize.value);
}

onMounted(() => {
    searchProduct();
    category.getData();
})

// ✅ Watcher: Real-time validation
watch(() => form.value.name, validateName);
watch(() => form.value.image, (newImage) => {
    if (newImage && !allowedTypes.includes(newImage.type)) {
        imageError.value = "Only JPEG, PNG, JPG, GIF, and SVG files are allowed.";
        form.value.image = null;
        imagePreview.value = null;
    } else {
        imageError.value = "";
    }
});
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

        <div class="mb-2">
            <input class="w-3xs px-4 py-2 border rounded-lg me-1.5 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Search Product name" v-model="searchKey" @input="searchProduct" type="text">

            <select v-model="paginateSize" @change="searchProduct" class="w-3xs px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">Sort By Paginate Size</option>
            <option value="2">2</option>
            <option value="5">5</option>
            <option value="10">10</option>
            </select>
        </div>

        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="py-3 px-6 text-left">#</th>
                    <th class="py-3 px-6 text-left">Image</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="text-gray-600 text-sm divide-y divide-gray-200">

                <tr class="hover:bg-gray-50" v-for="(data, i) in product?.products?.data" :key="i">
                    <td class="py-4 px-6">{{ i+1 }}</td>
                    <td class="py-4 px-6">
                        <img :src="data?.image" width="30" alt="">
                    </td>
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

        <div class="flex items-center justify-center py-10 lg:px-0 sm:px-6 px-4">
            <div class="lg:w-3/5 w-full flex items-center justify-between border-t border-gray-200">
                <!-- Previous Button -->
                <button
                    :disabled="!product?.products?.prev_page_url"
                    @click="searchProduct(product?.products?.current_page - 1)"
                    class="flex items-center pt-3 text-gray-600 hover:text-indigo-700 cursor-pointer"
                >
                    <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.1665 4H12.8332" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1.1665 4L4.49984 7.33333" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1.1665 4.00002L4.49984 0.666687" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-sm ml-3 font-medium leading-none">Previous</p>
                </button>

                <!-- Page Numbers -->
                <div class="sm:flex hidden">
                    <button
                        v-for="page in product?.products?.last_page"
                        :key="page"
                        @click="searchProduct(page)"
                        class="text-sm font-medium leading-none cursor-pointer text-gray-600 hover:text-indigo-700 hover:border-t hover:border-indigo-400 pt-3 mr-4 px-2"
                        :class="{ 'text-indigo-700 border-t border-indigo-400': page === product?.products?.current_page }"
                    >
                        {{ page }}
                    </button>
                </div>

                <!-- Next Button -->
                <button
                    :disabled="!product?.products?.next_page_url"
                    @click="searchProduct(product?.products?.current_page + 1)"
                    class="flex items-center pt-3 text-gray-600 hover:text-indigo-700 cursor-pointer"
                >
                    <p class="text-sm font-medium leading-none mr-3">Next</p>
                    <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.1665 4H12.8332" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.5 7.33333L12.8333 4" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.5 0.666687L12.8333 4.00002" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

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

                    <div class="mb-4">
                        <span @click="categories.splice(ii, 1)" class="relative me-2 bg-gray-300 p-1 pb-2 pr-6 rounded-md before:content-['x'] before:absolute before:-right-[-5px] before:-top-0.5 before:text-red-500 before:cursor-pointer before:text-2xl" v-for="(tag, ii) in categories" :key="ii">{{ tag?.name }}</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Categories</label>
                        <select @change="selectCategory" v-model="categoryData" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                          <option value="">Select Categories</option>
                          <option :value="data" :selected="categories.includes(data)" v-for="(data, ii) in category?.categories" :key="ii">{{ data?.name }}</option>
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
