import { defineStore } from 'pinia';
import authApi from '../services/auth';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    verificationUserId: null,
  }),

  getters: {
    isLoggedIn: (state) => !!state.token,
    isVerified: (state) => state.user?.is_verified ?? false,
  },

  actions: {
    async register(userData) {
      const response = await authApi.register(userData);
      this.verificationUserId = response.data.user_id;
      return response;
    },

    async verifyEmail(code) {
      const response = await authApi.verifyEmail(this.verificationUserId, code);
      this.verificationUserId = null;
      return response;
    },

    async resendVerification() {
      return await authApi.resendVerification(this.verificationUserId);
    },

    async login(credentials) {
      const response = await authApi.login(credentials);
      this.token = response.data.token;
      this.user = response.data.user;
      authApi.setToken(response.data.token);
      return response;
    },

    async logout() {
      try {
        await authApi.logout();
      } finally {
        this.token = null;
        this.user = null;
        authApi.removeToken();
      }
    },

    async changePassword(data) {
      const response = await authApi.changePassword(data);
      // Kijelentkeztetjük a felhasználót biztonsági okokból
      this.token = null;
      this.user = null;
      authApi.removeToken();
      return response;
    },

    async forgotPassword(email) {
      return await authApi.forgotPassword(email);
    },

    async resetPassword(data) {
      return await authApi.resetPassword(data);
    },

    async fetchUser() {
      try {
        const response = await authApi.get('/user');
        this.user = response.data;
        return response;
      } catch (error) {
        this.logout();
        throw error;
      }
    },
  },
});