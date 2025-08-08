import axios from "axios";
import { useAccessToken } from "@/composables/accessToken.ts";

const useAxios = () => {
  const { refreshAccessToken } = useAccessToken();

  const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    headers: {
      "Content-Type": "application/json",
    },
  });

  // Add request interceptor to dynamically set authorization header
  axiosInstance.interceptors.request.use((config) => {
    // ensure that the updated access-token is used for all api calls
    const accessToken = refreshAccessToken()

    if (accessToken) {
      config.headers.Authorization = `Bearer ${accessToken}`;
    } else {
      delete config.headers.Authorization;
    }
    return config;
  });


  return {
    axios: axiosInstance
  };
};

export default useAxios;