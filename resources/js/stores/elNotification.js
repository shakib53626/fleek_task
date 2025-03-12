import { ElNotification } from 'element-plus'
import { defineStore } from 'pinia';

export const useElNotifyStore = defineStore("el-notify", {
    state:() => ({

     }),

     getters:{},

     actions:{
        Success(msg){
            ElNotification({
                title: 'Success',
                message: msg,
                type: 'success',
                position: 'top-left',
            })
        },
        Warning(msg){
            ElNotification({
                title: 'Warning',
                message: msg,
                type: 'warning',
                position: 'top-left',
            })
        },
        Info(msg){
            ElNotification({
                title: 'Info',
                message: msg,
                type: 'info',
                position: 'top-left',
            })
        },
        Error(msg){
            ElNotification({
                title: 'Error',
                message: msg,
                type: 'error',
                position: 'top-left',
            })
        },
     },

})