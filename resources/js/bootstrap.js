import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// window.axios.defaults.xsrfCookieName is configured below.
// Inertia/Axios will automatically read this cookie and set the X-XSRF-TOKEN header.

window.axios.defaults.xsrfCookieName = 'ppdb-XSRF-TOKEN';

window.axios.defaults.withCredentials = true;
