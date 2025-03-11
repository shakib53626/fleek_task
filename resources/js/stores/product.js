// stores/counter.js
import { defineStore } from 'pinia'
import axiosInstance from '../service/axiosService';

export const useProductStore = defineStore('product', {
  state: () => ({
    products : true,
  }),

//   persist: true,

  actions: {
    async getData(){
        try {
            const res = await axiosInstance.get('/products');
            if(res?.data?.success){
                this.products = res?.data?.result;
            }

        } catch (error) {

            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

    async insert(api, data){
        try {

            const res = await axiosInstance.post(api, data);
            if(res?.data?.success){
                return res?.data;
            }

        } catch (error) {
            if(error?.response?.data){
                return error?.response?.data;
            }
        }
    },

    async destroy(data){
        try {
            const res = await axiosInstance.delete(`/products/${data.id}`);
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
