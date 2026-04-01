import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// Send Laravel's CSRF token on every request (avoids 419 Page Expired on POST/PUT/PATCH/DELETE)
window.axios.interceptors.request.use((config) => {
  let token = null;
  const cookie = document.cookie.split('; ').find((row) => row.startsWith('XSRF-TOKEN='));
  if (cookie) {
    token = decodeURIComponent(cookie.split('=')[1]);
  }
  if (!token) {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta && meta.getAttribute('content')) token = meta.getAttribute('content');
  }
  if (token) config.headers['X-XSRF-TOKEN'] = token;
  return config;
});
