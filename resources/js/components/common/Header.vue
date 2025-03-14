<script setup>
import Pusher from "pusher-js";
import { useRouter } from 'vue-router';
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useAuthStore, useNotificationStore, useSettingStore, useElNotifyStore } from '../../stores';

const router       = useRouter();
const auth         = useAuthStore();
const setting      = useSettingStore();
const elNotify     = useElNotifyStore();
const notification = useNotificationStore();

const dropdownOpen      = ref(false)
const notificationOpen  = ref(false);
const dropdownRef       = ref(null)
const dropdownNotifyRef = ref(null)

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const toggleNotify = () =>{
    notificationOpen.value = !notificationOpen.value
}

// Click outside handler
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value     = false;
  }
  if (dropdownNotifyRef.value && !dropdownNotifyRef.value.contains(event.target)) {
    notificationOpen.value = false;
  }
}

const updateNotify = async(id) =>{
    const res = await notification.update(id);
    notificationOpen.value = false;
}

const logout = async() =>{
    const res = await auth.logout();
    if(res?.success){
        router.push({name:'login'});
    }else{
        router.push({name:'login'});
    }
};

onMounted(() => {
    notification?.getData();
    document.addEventListener('click', handleClickOutside);

    Pusher.logToConsole = true;
    const pusher = new Pusher("6ca34b8e8d5c6539e6e2", {
        cluster: "ap2",
    });

    const channel = pusher.subscribe("notifications");

    // Ensure no duplicate listeners
    channel.unbind("new-notification");

    channel.bind("new-notification", function (data) {
        notification?.getData();
        elNotify.Success(data?.message?.message);
    });
});

const unreadCount = computed(() => {
  return Array.isArray(notification?.notifications)
    ? notification.notifications.filter(n => !n.is_read).length
    : 0;
});

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
              <div class="relative inline-block text-left" ref="dropdownNotifyRef">
                <!-- Dropdown Button -->
                <button @click="toggleNotify"
                    class="relative inline-flex items-center cursor-pointer w-full justify-center gap-x-1.5 rounded-md px-3 py-2 text-sm font-semibold text-gray-100 shadow-xs  hover:bg-gray-700"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    <span class="absolute top-0 right-0 w-[20px] h-[20px] bg-gray-300 text-gray-900 text-xs leading-5 rounded-full">{{unreadCount}}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"></path></svg>

                </button>

                <!-- Dropdown Menu -->
                <div v-show="notificationOpen" class="absolute p-2.5 right-0 z-10 mt-2 w-64 origin-top-right divide-y divide-gray-600 rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-none">
                  <div class="py-1">
                    <h2 class="text-gray-900 font-semibold">Real-time Notifications</h2>
                    <ul>
                        <li class="text-gray-700 text-sm border-b last:border-b-0 border-gray-200 p-2"
                            :class="{'bg-blue-300/20' : !notify?.is_read}"
                            v-for="(notify, index) in notification?.notifications" :key="index"
                            @click="router.push({name : notify?.url}), updateNotify(notify?.id)"
                            style="cursor: pointer;">
                            {{ notify.message }}
                        </li>
                    </ul>
                  </div>
                </div>
              </div>

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
