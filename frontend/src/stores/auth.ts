import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { useAccessToken } from "@/composables/accessToken.ts";
import useAxios from "@/composables/axios.ts";
import type { AuthResponse, RegisterRequest, User } from "@/utils/types";

export const useAuthStore = defineStore("auth", () => {
  const { accessToken, setAccessToken, clearAccessToken } = useAccessToken();
  const { axios } = useAxios();

  const user = ref<User | null>(null);
  const isAuthenticated = computed(() => !!accessToken.value);

  const login = async (credentials: { email: string; password: string }) => {
    try {
      const response = await axios.post("/api/auth/token/create", credentials);
      const { user: userData, access_token: authToken }: AuthResponse =
        response.data;

      user.value = userData;
      setAccessToken(authToken);

      return { success: true };
    } catch (error: any) {
      return {
        success: false,
        message: error.response?.data?.message || "Login failed",
      };
    }
  };

  const register = async (payload: RegisterRequest) => {
    try {
      const response = await axios.post("/api/auth/register", payload);
      const { user: userData, access_token: authToken }: AuthResponse =
        response.data;

      user.value = userData;
      setAccessToken(authToken);

      return { success: true };
    } catch (error: any) {
      return {
        success: false,
        message: error.response?.data?.message || "Registration failed",
      };
    }
  };

  const logout = async () => {
    try {
      await axios.post("/api/me/logout");
    } catch (error) {
      console.error("Logout error:", error);
    } finally {
      user.value = null;
      clearAccessToken();
    }
  };

  const checkAuth = async () => {
    if (!accessToken.value) return false;

    try {
      const response = await axios.get("/api/users/me");
      user.value = response.data as User;

      return true;
    } catch (error) {
      await logout();
      return false;
    }
  };

  return {
    user,
    isAuthenticated,
    login,
    register,
    logout,
    checkAuth,
  };
});
