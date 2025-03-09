<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router';
import { useAuthStore, useSettingStore } from '../../stores';

const setting = useSettingStore();
const auth    = useAuthStore();
const router  = useRouter();

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

const logout = async() =>{
    const res = await auth.logout();
    if(res?.success){
        router.push({name:'login'});
    }
};

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
              Welcome to Task admin panel
            </div>

            <div class="">
              <div class="relative inline-block text-left" ref="dropdownRef">
                <!-- Dropdown Button -->
                <button @click="toggleDropdown"
                  class="inline-flex items-center cursor-pointer w-full justify-center gap-x-1.5 rounded-md px-3 py-2 text-sm font-semibold text-gray-100 shadow-xs  hover:bg-gray-700"
                  id="menu-button" aria-expanded="true" aria-haspopup="true">

                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);"><path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path></svg>
                  {{ auth?.user?.name }}
                  <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                      clip-rule="evenodd" />
                  </svg>

                </button>

                <!-- Dropdown Menu -->
                <div v-show="dropdownOpen"
                  class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-none">
                  <!-- <div class="py-1">
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
                  </div> -->
                  <div class="py-1">
                    <a href="javascript:void(0)" @click="logout" class="flex items-center px-4 py-2 text-sm text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M19.002 3h-14c-1.103 0-2 .897-2 2v4h2V5h14v14h-14v-4h-2v4c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.898-2-2-2z"></path><path d="m11 16 5-4-5-4v3.001H3v2h8z"></path></svg>
                        <span class="pl-1">Logout</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
        </header>
    </div>
</template>

<style scoped>

</style>
