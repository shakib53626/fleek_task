import { defineStore } from 'pinia';

export const useTokenStore = defineStore('token', {
    state:() => ({
        token : null,
     }),

     persist: true,

     getters:{
        getToken: (state) => {
            return state.token;
        },
     },

     actions:{
        setToken(token){
            this.token = token;
        },

        removeToken(){
            this.$reset();
        }
     },

})
