// API Configuration
import TokenManager from "./token_manager.js";

const API_BASE_URL = 'http://127.0.0.1:8000/api';

// API service functions
export const apiService = {
    // Create headers with authentication
    createHeaders: (includeAuth = true) => {
        const headers = new Headers();
        headers.append("Content-Type", "application/json");
        headers.append("Accept", "application/json");

        if (includeAuth && TokenManager.isAuthenticated()) {
            headers.append("Authorization", `Bearer ${TokenManager.getApiKey()}`);
        }

        return headers;
    },

    // Create form headers with authentication
    createFormHeaders: (includeAuth = true) => {
        const headers = new Headers();
        headers.append("Accept", "application/json");

        if (includeAuth && TokenManager.isAuthenticated()) {
            headers.append("Authorization", `Bearer ${TokenManager.getApiKey()}`);
        }

        return headers;
    },

    // User login
    login: async (email, password) => {
        try {
            const formdata = new FormData();
            formdata.append("email", email);
            formdata.append("password", password);

            const requestOptions = {
                method: "POST",
                headers: apiService.createFormHeaders(false), // Don't include auth for login
                body: formdata,
                redirect: "follow"
            };

            const response = await fetch(`${API_BASE_URL}/login`, requestOptions);

            if (!response.ok) {
                throw new Error(`Login failed! status: ${response.status}`);
            }

            const data = await response.json();

            // Save token if login successful
            if (data.apiKey) {
                TokenManager.setToken(data);
            }

            return data;
        } catch (error) {
            console.error('Error during login:', error);
            throw error;
        }
    },

    // User signup
    signup: async (userData) => {
        try {
            const formdata = new FormData();
            Object.keys(userData).forEach(key => {
                formdata.append(key, userData[key]);
            });

            const requestOptions = {
                method: "POST",
                headers: apiService.createFormHeaders(false), // Don't include auth for signup
                body: formdata,
                redirect: "follow"
            };

            const response = await fetch(`${API_BASE_URL}/signup`, requestOptions);

            if (!response.ok) {
                throw new Error(`Signup failed! status: ${response.status}`);
            }

            const data = await response.json();

            // Save token if signup successful and returns token
            if (data.apiKey) {
                TokenManager.setToken(data);
            }

            return data;
        } catch (error) {
            console.error('Error during signup:', error);
            throw error;
        }
    },

    // User logout
    logout: () => {
        TokenManager.removeToken();
    },

    // Fetch all prompts (with authentication)
    fetchPrompts: async () => {
        try {
            const requestOptions = {
                method: "GET",
                headers: apiService.createHeaders(),
                redirect: "follow"
            };

            const response = await fetch(`${API_BASE_URL}/prompts`, requestOptions);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching prompts:', error);
            throw error;
        }
    },

    // Fetch prompts by category (with authentication)
    fetchPromptsByCategory: async (category) => {
        try {
            const requestOptions = {
                method: "GET",
                headers: apiService.createHeaders(),
                redirect: "follow"
            };

            const response = await fetch(`${API_BASE_URL}/prompts/category/${category}`, requestOptions);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching prompts by category:', error);
            throw error;
        }
    },

    // Fetch a single prompt by ID (with authentication)
    fetchPromptById: async (id) => {
        try {
            const requestOptions = {
                method: "GET",
                headers: apiService.createHeaders(),
                redirect: "follow"
            };

            const response = await fetch(`${API_BASE_URL}/prompts/${id}`, requestOptions);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching prompt by ID:', error);
            throw error;
        }
    }
};

// Usage examples:

// 1. Login user
const loginUser = async (email, password) => {
    try {
        const result = await apiService.login(email, password);
        console.log('Login successful:', result);
        return result;
    } catch (error) {
        console.error('Login failed:', error);
        throw error;
    }
};

// 2. Signup user
const signupUser = async (userData) => {
    try {
        const result = await apiService.signup(userData);
        console.log('Signup successful:', result);
        return result;
    } catch (error) {
        console.error('Signup failed:', error);
        throw error;
    }
};

// 3. Logout user
const logoutUser = () => {
    apiService.logout();
    console.log('User logged out');
};

// 4. Check if user is authenticated
const isUserAuthenticated = () => {
    return TokenManager.isAuthenticated();
};

// 5. Fetch all prompts (requires authentication)
const loadAllPrompts = async () => {
    try {
        if (!TokenManager.isAuthenticated()) {
            throw new Error('User not authenticated');
        }
        const prompts = await apiService.fetchPrompts();
        console.log('All prompts loaded:', prompts);
        return prompts;
    } catch (error) {
        console.error('Failed to load prompts:', error);
        throw error;
    }
};

// 6. Fetch prompts by category (requires authentication)
const loadPromptsByCategory = async (category) => {
    try {
        if (!TokenManager.isAuthenticated()) {
            throw new Error('User not authenticated');
        }
        const prompts = await apiService.fetchPromptsByCategory(category);
        console.log(`Prompts in category '${category}' loaded:`, prompts);
        return prompts;
    } catch (error) {
        console.error(`Failed to load prompts for category '${category}':`, error);
        throw error;
    }
};

// 7. Fetch a specific prompt by ID (requires authentication)
const loadPromptById = async (promptId) => {
    try {
        if (!TokenManager.isAuthenticated()) {
            throw new Error('User not authenticated');
        }
        const prompt = await apiService.fetchPromptById(promptId);
        console.log(`Prompt with ID ${promptId} loaded:`, prompt);
        return prompt;
    } catch (error) {
        console.error(`Failed to load prompt with ID ${promptId}:`, error);
        throw error;
    }
};

// Export individual functions for direct use
export const login = apiService.login;
export const signup = apiService.signup;
export const logout = apiService.logout;
export const isAuthenticated = TokenManager.isAuthenticated;
export const fetchPrompts = apiService.fetchPrompts;
export const fetchPromptsByCategory = apiService.fetchPromptsByCategory;
export const fetchPromptById = apiService.fetchPromptById;
