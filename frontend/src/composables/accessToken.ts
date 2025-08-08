import { ref } from "vue";

export const useAccessToken = () => {
  const accessToken = ref(localStorage.getItem("access-token") || null);

  const setAccessToken = (token: string) => {
    accessToken.value = token;
    localStorage.setItem("access-token", token);
  };

  const clearAccessToken = () => {
    accessToken.value = null;
    localStorage.removeItem("access-token");
  };

  const refreshAccessToken = () => {
    accessToken.value = localStorage.getItem('access-token');
    return accessToken.value;
  }

  return {
    accessToken,
    refreshAccessToken,
    setAccessToken,
    clearAccessToken,
  };
};
