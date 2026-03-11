import axios from 'axios';

const api = axios.create({
  baseURL: 'https://hasznaltauto.alwaysdata.net/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Token hozzáadása minden kéréshez
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default {
  // Regisztráció
  register(userData) {
    return api.post('/register', userData);
  },
  
  verifyEmail(userId, code) {
    return api.post('/verify-email', { user_id: userId, code });
  },
  
  resendVerification(userId) {
    return api.post('/resend-verification', { user_id: userId });
  },
  
  // Bejelentkezés
  login(credentials) {
    return api.post('/login', credentials);
  },
  
  logout() {
    return api.post('/logout');
  },
  
  // Elfelejtett jelszó
  forgotPassword(email) {
    return api.post('/forgot-password', { email });
  },
  
  resetPassword(data) {
    return api.post('/reset-password', data);
  },
  
  validateResetToken(token, email) {
    return api.post('/validate-reset-token', { token, email });
  },
  
  // Jelszóváltoztatás
  changePassword(data) {
    return api.post('/change-password', data);
  },
  
  // Segédfüggvények
  setToken(token) {
    localStorage.setItem('token', token);
  },
  
  getToken() {
    return localStorage.getItem('token');
  },
  
  removeToken() {
    localStorage.removeItem('token');
  },
  
  isLoggedIn() {
    return !!this.getToken();
  }
};