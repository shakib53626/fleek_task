import { defineStore } from 'pinia'
import axiosInstance from '../service/axiosService';

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications : true,
  }),

//   persist: true,

  actions: {
    async getData(){
        try {
            const res = await axiosInstance.get('/notifications');
            if(res?.data?.success){
                this.notifications = res?.data?.result;
            }

        } catch (error) {

            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

    async insert(data){
        try {

            const res = await axiosInstance.post('/notifications', data);
            if(res?.data?.success){
                return res?.data;
            }

        } catch (error) {
            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

    async update(id){
        try {

            const res = await axiosInstance.put(`/notifications/${id}`);
            if(res?.data?.success){
                this.getData();
            }

        } catch (error) {
            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },
  },
})
