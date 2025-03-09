// stores/counter.js
import { defineStore } from 'pinia'

export const useSettingStore = defineStore('setting', {
  state: () => ({
    sidebarOpen : true,
  }),

  persist: true,
  
  actions: {

  },
})