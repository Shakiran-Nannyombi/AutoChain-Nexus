import './bootstrap';
import { createApp } from 'vue';
import Register from './components/Register.vue';
import UserApprovals from './components/admin/UserApprovals.vue';

// Create Vue app
const app = createApp({});

// Register components
app.component('register-form', Register);
app.component('user-approvals', UserApprovals);

// Mount the app
app.mount('#app');
