<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useSettingStore } from '../../stores';

const setting = useSettingStore();

const dropdownOpen = ref(false)
const dropdownRef = ref(null)

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

// Click outside handler
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
    <div>
        <header class="fixed top-0 right-0 transition-all duration-300 flex items-center justify-between p-4 text-semibold text-gray-100 bg-gray-900" :class="setting.sidebarOpen ? 'w-[calc(100%-16rem)] ml-64' : 'w-full ml-0'">
            <div class="flex items-center">
              <button class="p-1 mr-4 cursor-pointer" @click="setting.sidebarOpen = !setting.sidebarOpen">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
              Welcome to spark event solution admin panel
            </div>
    
            <div class="">
              <div class="relative inline-block text-left" ref="dropdownRef">
                <!-- Dropdown Button -->
                <button @click="toggleDropdown"
                  class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50"
                  id="menu-button" aria-expanded="true" aria-haspopup="true">
                  Options
                  <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
            
                <!-- Dropdown Menu -->
                <div v-show="dropdownOpen"
                  class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-none">
                  <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700">Edit</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700">Duplicate</a>
                  </div>
                  <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700">Archive</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700">Move</a>
                  </div>
                  <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700">Share</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700">Add to favorites</a>
                  </div>
                  <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700">Delete</a>
                  </div>
                </div>
              </div>
            </div>
        </header>
    </div>
</template>

<style scoped>

</style>