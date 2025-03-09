<script setup>

</script>

<template>
    <div class="overflow-x-auto mt-5">
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
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6">1</td>
                    <td class="py-4 px-6">John Doe</td>
                    <td class="py-4 px-6">john@example.com</td>
                    <td class="py-4 px-6">Admin</td>
                    <td class="py-4 px-6 flex justify-center space-x-2">
                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Edit</button>
                        <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Delete</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6">2</td>
                    <td class="py-4 px-6">Jane Smith</td>
                    <td class="py-4 px-6">jane@example.com</td>
                    <td class="py-4 px-6">User</td>
                    <td class="py-4 px-6 flex justify-center space-x-2">
                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Edit</button>
                        <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<style scoped>

</style>
