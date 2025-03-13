// stores/counter.js
import { defineStore } from 'pinia'
import axiosInstance from '../service/axiosService';
import { useNotificationStore } from '.';

export const useCategoryStore = defineStore('category', {
  state: () => ({
    categories : true,
  }),

//   persist: true,

  actions: {
    async getData(){
        try {
            const res = await axiosInstance.get('/categories');
            if(res?.data?.success){
                this.categories = res?.data?.result;
            }

        } catch (error) {

            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

    async insert(api, data){
        try {
            const notify = useNotificationStore();
            if(data?.id){
                const res = await axiosInstance.put(api, data);
                if(res?.data?.success){
                    return res?.data;
                }
            }else{
                const res = await axiosInstance.post(api, data);
                if(res?.data?.success){
                    notify.insert({
                        message : `New Category added: ${res?.data?.result?.name}! Explore now. 🚀🛍️`,
                        url : 'categories'
                    });
                    return res?.data;
                }
            }

        } catch (error) {
            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

    async destroy(data){
        try {
            const res = await axiosInstance.delete(`/categories/${data.id}`);
            if(res?.data?.success){
                return res?.data;
            }
        } catch (error) {
            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

  },
})
