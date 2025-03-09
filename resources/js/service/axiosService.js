import axios from 'axios';
import { useAuthStore, useTokenStore } from '../stores';

const axiosInstance = axios.create({
  baseURL: '/api',
});

axiosInstance.interceptors.request.use(
function (config) {
    const token = useTokenStore();
    config.headers['Authorization'] = `Bearer ${token.getToken}`;
    return config;
}, function (error) {
return Promise.reject(error);
});

axiosInstance.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response && error.response.status === 401) {
      const authInfo = useAuthStore();
      const token = useTokenStore();
      authInfo.user = {};
      token.$reset();
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;
