<script setup>
import { Sidebar, Header } from '../components'
import { useSettingStore } from '../stores'

const setting = useSettingStore();
</script>

<template>
    <div class="flex overflow-x-hidden h-screen">
        <aside
          class="fixed top-0 left-0 min-h-screen flex-shrink-0 w-64 flex flex-col border-r transition-all duration-300" :class="{ '-ml-64': !setting.sidebarOpen }" >
          <Sidebar/>
        </aside>
    
        <div class="flex-1 transition-all duration-300" :class="{'ml-64' : setting.sidebarOpen}">
          <Header/>
          <main class="p-4 pt-18">
            <slot/>
          </main>
        </div>
      </div>
</template>

<style scoped>

</style>