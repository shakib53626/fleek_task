// stores/counter.js
import { defineStore } from 'pinia'
import axiosInstance from '../service/axiosService';

export const useUserStore = defineStore('user', {
  state: () => ({
    users : true,
  }),

//   persist: true,

  actions: {
    async getData(){
        try {
            const res = await axiosInstance.get('/users');
            if(res?.data?.success){
                this.users = res?.data?.result;
            }

        } catch (error) {

            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

    async insert(api, data){
        try {

            if(data?.id){
                const res = await axiosInstance.put(api, data);
                if(res?.data?.success){
                    return res?.data;
                }
            }else{
                const res = await axiosInstance.post(api, data);
                if(res?.data?.success){
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
            const res = await axiosInstance.delete(`/users/${data.id}`);
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
