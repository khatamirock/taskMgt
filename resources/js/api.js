// API base URL
const API_URL = '/api';

const api = {
    // Save token to local storage
    setToken(token) {
        localStorage.setItem('auth_token', token);
    },

    // Get token from local storage
    getToken() {
        return localStorage.getItem('auth_token');
    },

    // Remove token
    removeToken() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
    },

    // Helper for fetch calls
    async call(endpoint, options = {}) {
        const token = this.getToken();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(options.headers || {})
        };

        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const response = await fetch(`${API_URL}${endpoint}`, {
            ...options,
            headers
        });

        if (response.status === 401) {
            this.removeToken();
            window.location.href = '/';
            return;
        }

        return response;
    },

    // Auth actions
    async login(email, password) {
        const response = await this.call('/login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();
        if (response.ok) {
            this.setToken(data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
        }
        return { ok: response.ok, data };
    },

    async logout() {
        await this.call('/logout', { method: 'POST' });
        this.removeToken();
        window.location.href = '/';
    },

    // Project actions
    async getProjects() {
        const response = await this.call('/projects');
        return await response.json();
    },

    getUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    },


    async get_totalUsers() {
        const resp = await this.call('/users/count');
        return await resp.json();
    },



    
    // Task actions
    async getTasks() {
        const response = await this.call('/tasks');
        return await response.json();
    },

    async createTask(taskData) {
        const response = await this.call('/tasks', {
            method: 'POST',
            body: JSON.stringify(taskData)
        });
        return await response.json();
    },

    async updateTaskStatus(taskId, status) {
        const response = await this.call(`/tasks/${taskId}/status`, {
            method: 'PATCH',
            body: JSON.stringify({ status })
        });
        return await response.json();
    }
};

export default api;
