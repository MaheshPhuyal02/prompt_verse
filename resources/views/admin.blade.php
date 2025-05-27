<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PromptVerse Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .neural-pattern {
            background-image: radial-gradient(circle at 25% 25%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
            linear-gradient(45deg, transparent 30%, rgba(139, 92, 246, 0.05) 50%, transparent 70%);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 to-indigo-950 text-gray-100">
<!-- Neural network effect in background -->
<div class="absolute inset-0 opacity-5 pointer-events-none">
    <svg width="100%" height="100%">
        <pattern id="neural-net" width="50" height="50" patternUnits="userSpaceOnUse">
            <path d="M25,0 L50,25 L25,50 L0,25 Z" fill="none" stroke="currentColor" stroke-width="1"/>
            <circle cx="25" cy="25" r="3" fill="currentColor"/>
        </pattern>
        <rect width="100%" height="100%" fill="url(#neural-net)"/>
    </svg>
</div>

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-800 bg-opacity-90 backdrop-blur-lg rounded-xl p-8 w-full max-w-md border border-gray-700">
        <h2 class="text-2xl font-bold mb-6 text-center bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
            Login to PromptVerse
        </h2>
        <form id="loginForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" id="loginEmail" required
                       class="w-full px-4 py-3 bg-gray-700 bg-opacity-50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                       placeholder="Enter your email">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <input type="password" id="loginPassword" required
                       class="w-full px-4 py-3 bg-gray-700 bg-opacity-50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                       placeholder="Enter your password">
            </div>
            <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                Login
            </button>
        </form>
        <div id="loginError" class="mt-4 text-red-400 text-sm hidden"></div>
    </div>
</div>

<!-- Main App Container -->
<div id="mainApp" class="hidden">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 bg-opacity-30 backdrop-blur-lg border-r border-gray-700">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                    PromptVerse
                </h1>
                <p class="text-gray-400 text-sm mt-1">Admin Dashboard</p>
            </div>

            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <button
                            class="nav-btn w-full flex items-center px-4 py-3 text-left rounded-lg hover:bg-gray-700 hover:bg-opacity-50 transition-all duration-200 active"
                            data-section="dashboard">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </button>
                    </li>
                    <li>
                        <button
                            class="nav-btn w-full flex items-center px-4 py-3 text-left rounded-lg hover:bg-gray-700 hover:bg-opacity-50 transition-all duration-200"
                            data-section="prompts">
                            <i class="fas fa-lightbulb mr-3"></i>
                            Prompts
                        </button>
                    </li>
                    <li>
                        <button
                            class="nav-btn w-full flex items-center px-4 py-3 text-left rounded-lg hover:bg-gray-700 hover:bg-opacity-50 transition-all duration-200"
                            data-section="files">
                            <i class="fas fa-file-upload mr-3"></i>
                            Files
                        </button>
                    </li>
                    <li>
                        <button
                            class="nav-btn w-full flex items-center px-4 py-3 text-left rounded-lg hover:bg-gray-700 hover:bg-opacity-50 transition-all duration-200"
                            data-section="purchases">
                            <i class="fas fa-shopping-cart mr-3"></i>
                            Purchases
                        </button>
                    </li>
                </ul>
            </nav>

            <div class="absolute bottom-4 left-4 right-4">
                <button id="logoutBtn"
                        class="w-full flex items-center px-4 py-3 text-left rounded-lg hover:bg-red-600 hover:bg-opacity-50 transition-all duration-200 text-red-400">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Dashboard Section -->
            <section id="dashboard" class="content-section">
                <div class="mb-8 bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700">
                    <h2 class="text-3xl font-bold mb-2">Dashboard Overview</h2>
                    <p class="text-gray-400">Welcome to PromptVerse Admin Panel</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm">Total Users</p>
                                <p class="text-3xl font-bold text-blue-400">1,247</p>
                            </div>
                            <i class="fas fa-users text-3xl text-blue-400 opacity-50"></i>
                        </div>
                    </div>
                    <div
                        class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm">Active Prompts</p>
                                <p class="text-3xl font-bold text-purple-400">856</p>
                            </div>
                            <i class="fas fa-lightbulb text-3xl text-purple-400 opacity-50"></i>
                        </div>
                    </div>
                    <div
                        class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm">Files Uploaded</p>
                                <p class="text-3xl font-bold text-green-400">2,391</p>
                            </div>
                            <i class="fas fa-file-upload text-3xl text-green-400 opacity-50"></i>
                        </div>
                    </div>
                    <div
                        class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm">Total Revenue</p>
                                <p class="text-3xl font-bold text-yellow-400">$12,450</p>
                            </div>
                            <i class="fas fa-dollar-sign text-3xl text-yellow-400 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Prompts Section -->
            <section id="prompts" class="content-section hidden">
                <div class="mb-8 bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700">
                    <h2 class="text-3xl font-bold mb-2">Prompt Management</h2>
                    <p class="text-gray-400">Create and manage AI prompts</p>
                </div>

                <!-- Add Prompt Form -->
                <div class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 mb-8">
                    <h3 class="text-xl font-semibold mb-6 flex items-center">
                        <i class="fas fa-plus-circle mr-2 text-green-400"></i>
                        Add New Prompt
                    </h3>
                    <form id="promptForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Prompt Title</label>
                                <input type="text" id="promptTitle" required
                                       class="w-full px-4 py-3 bg-gray-700 bg-opacity-50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                                       placeholder="Enter prompt title">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                                <select id="promptCategory" required
                                        class="w-full px-4 py-3 bg-gray-700 bg-opacity-50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                                    <option value="">Select category</option>
                                    <option value="writing">Writing</option>
                                    <option value="coding">Coding</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="design">Design</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Prompt Content</label>
                            <textarea id="promptContent" rows="4" required
                                      class="w-full px-4 py-3 bg-gray-700 bg-opacity-50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                                      placeholder="Enter prompt content"></textarea>
                        </div>

                        <!-- Image Upload Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Prompt Image (Optional)</label>
                            <div
                                class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center bg-gray-700 bg-opacity-30 hover:bg-opacity-50 transition-all duration-200 cursor-pointer"
                                id="imageDropZone">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-300 mb-2">Drag & drop an image here, or click to browse</p>
                                <p class="text-gray-500 text-sm">Supports JPG, PNG, GIF up to 5MB</p>
                                <input type="file" id="promptImage" accept="image/*" class="hidden">
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="previewImg" class="max-w-xs rounded-lg border border-gray-600" alt="Preview">
                                <button type="button" id="removeImage" class="ml-4 text-red-400 hover:text-red-300">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                                class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Create Prompt
                        </button>
                    </form>
                </div>

                <!-- Prompts List -->
                <div
                    class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl border border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                        <h3 class="text-xl font-semibold">All Prompts</h3>
                        <button id="refreshPrompts"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700 bg-opacity-30">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Title
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody id="promptsList" class="divide-y divide-gray-700">
                            <!-- Prompts will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Files Section -->
            <section id="files" class="content-section hidden">
                <div class="mb-8 bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700">
                    <h2 class="text-3xl font-bold mb-2">File Management</h2>
                    <p class="text-gray-400">Upload and manage files</p>
                </div>

                <!-- File Upload -->
                <div class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 mb-8">
                    <h3 class="text-xl font-semibold mb-6 flex items-center">
                        <i class="fas fa-upload mr-2 text-blue-400"></i>
                        Upload Files
                    </h3>
                    <div
                        class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center bg-gray-700 bg-opacity-30 hover:bg-opacity-50 transition-all duration-200 cursor-pointer"
                        id="fileDropZone">
                        <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
                        <p class="text-xl text-gray-300 mb-2">Drag & drop files here, or click to browse</p>
                        <p class="text-gray-500">Support for multiple file types</p>
                        <input type="file" id="fileUpload" multiple class="hidden">
                    </div>
                </div>

                <!-- Files List -->
                <div
                    class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl border border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-700">
                        <h3 class="text-xl font-semibold">Uploaded Files</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700 bg-opacity-30">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    File ID
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Size
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-300">820fe2fd...</td>
                                <td class="px-6 py-4 text-sm text-gray-300">image.jpg</td>
                                <td class="px-6 py-4 text-sm text-gray-300">Image</td>
                                <td class="px-6 py-4 text-sm text-gray-300">2.5 MB</td>
                                <td class="px-6 py-4 text-sm">
                                    <button
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2 transition duration-200">
                                        View
                                    </button>
                                    <button
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-200">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Purchases Section -->
            <section id="purchases" class="content-section hidden">
                <div class="mb-8 bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700">
                    <h2 class="text-3xl font-bold mb-2">Purchase Management</h2>
                    <p class="text-gray-400">Track user purchases and transactions</p>
                </div>

                <div
                    class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl border border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                        <h3 class="text-xl font-semibold">Recent Purchases</h3>
                        <button id="refreshPurchases"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700 bg-opacity-30">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Item
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                            </tr>
                            </thead>
                            <tbody id="purchasesList" class="divide-y divide-gray-700">
                            <!-- Purchases will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-800 bg-opacity-90 backdrop-blur-lg rounded-xl p-6 flex items-center space-x-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-400"></div>
        <span class="text-white font-medium">Loading...</span>
    </div>
</div>

<script>
    // Configuration
    const API_URL = 'http://127.0.0.1:8000/api';
    let authToken = null;

    // Check for existing session
    const checkSession = () => {
        const token = sessionStorage.getItem('auth_token');
        if (token) {
            authToken = token;
            showMainApp();
        } else {
            showLoginModal();
        }
    };

    // Show/Hide functions
    const showLoginModal = () => {
        document.getElementById('loginModal').classList.remove('hidden');
        document.getElementById('mainApp').classList.add('hidden');
    };

    const showMainApp = () => {
        document.getElementById('loginModal').classList.add('hidden');
        document.getElementById('mainApp').classList.remove('hidden');
        loadDashboardData();
    };

    const showLoading = () => {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    };

    const hideLoading = () => {
        document.getElementById('loadingOverlay').classList.add('hidden');
    };

    const showError = (message, containerId = 'loginError') => {
        const errorElement = document.getElementById(containerId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        setTimeout(() => errorElement.classList.add('hidden'), 5000);
    };

    // API Helper
    const apiCall = async (endpoint, options = {}, data = {}) => {
        const config = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...options.headers
            },
            ...options
        };

        if (authToken) {
            config.headers['Authorization'] = `Bearer ${authToken}`;
        }

        if (data && Object.keys(data).length > 0) {
            config.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(`${API_URL}${endpoint}`, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    };

    // Login functionality
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;

        showLoading();

        try {
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);

            const response = await fetch(`${API_URL}/login`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.apiKey) {
                authToken = data.apiKey;
                sessionStorage.setItem('auth_token', authToken);
                showMainApp();
            } else {
                showError(data.message || 'Login failed');
            }
        } catch (error) {
            showError('Network error. Please try again.');
        } finally {
            hideLoading();
        }
    });

    // Logout functionality
    document.getElementById('logoutBtn').addEventListener('click', () => {
        authToken = null;
        sessionStorage.removeItem('auth_token');
        showLoginModal();
    });

    // Navigation
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Remove active class from all buttons
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active', 'bg-blue-600', 'bg-opacity-50'));
            // Add active class to clicked button
            e.target.classList.add('active', 'bg-blue-600', 'bg-opacity-50');

            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => section.classList.add('hidden'));
            // Show selected section
            const sectionId = e.target.dataset.section;
            document.getElementById(sectionId).classList.remove('hidden');

            // Load section-specific data
            loadSectionData(sectionId);
        });
    });

    // Prompt form submission
    document.getElementById('promptForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const title = document.getElementById('promptTitle').value;
        const content = document.getElementById('promptContent').value;
        const category = document.getElementById('promptCategory').value;

        showLoading();

        try {
            // For now, we'll just show success since the exact API endpoint structure isn't clear
            // upload file first

            if (!title || !content || !category) {
                throw new Error('All fields are required');
            }

            console.log("Upload :: " );

            const uploadForm = new FormData()
            uploadForm.append("file", fileUpload.files[0]);

            const uploadFile
                = apiCall("/file/upload",
                {
                    "method": "post"
                },

            )
            console.log("Uploaded :: " );

            if (uploadFile) {
                console.log('File uploaded successfully');

                const formData = new FormData();

                // title: Advanced ChatGPT Marketing Copy Generator
                // description: Create compelling marketing copy for social media, emails, and ads with this comprehensive prompt template
                // rating: 4.7
                // price: 15.99
                // category: Marketing
                // popular: 1

                formData.append('title', title);
                formData.append('description', content);
                formData.append('category', category);
                formData.append('popular', 1);


                const req = await apiCall('/prompts',
                    {
                        method: 'POST'
                    }, formData);

                if (req) {

                    alert('Prompt created successfully!');
                    document.getElementById('promptForm').reset();
                    document.getElementById('imagePreview').classList.add('hidden');
                } else {
                    throw new Error('File upload failed error : ' + req.data);

                }

            } else {
                throw new Error('File upload failed');
            }

        } catch (error) {
            alert('Failed to create prompt: ' + error.message);
        } finally {
            hideLoading();
        }
    });

    // Image upload handling
    const imageDropZone = document.getElementById('imageDropZone');
    const promptImage = document.getElementById('promptImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    imageDropZone.addEventListener('click', () => promptImage.click());

    promptImage.addEventListener('change', handleImageSelect);

    imageDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageDropZone.classList.add('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });

    imageDropZone.addEventListener('dragleave', () => {
        imageDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });

    imageDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        imageDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');

        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            promptImage.files = files;
            handleImageSelect();
        }
    });

    function handleImageSelect() {
        const file = promptImage.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('removeImage').addEventListener('click', () => {
        promptImage.value = '';
        imagePreview.classList.add('hidden');
    });

    // File upload handling
    const fileDropZone = document.getElementById('fileDropZone');
    const fileUpload = document.getElementById('fileUpload');
    fileDropZone.addEventListener('click', () => fileUpload.click());
    fileUpload.addEventListener('change', handleFileSelect);


    fileDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileDropZone.classList.add('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });

    fileDropZone.addEventListener('dragleave', () => {
        fileDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });


    fileDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        fileDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileUpload.files = files;
            handleFileSelect();
        }
    });

    function handleFileSelect() {
        const files = fileUpload.files;
        if (files.length > 0) {
            const fileList = Array.from(files).map(file => `
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-300">${file.name}</td>
                    <td class="px-6 py-4 text-sm text-gray-300">${file.type || 'Unknown'}</td>
                    <td class="px-6 py-4 text-sm text-gray-300">${(file.size / 1024).toFixed(2)} KB</td>
                    <td class="px-6 py-4 text-sm">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2 transition duration-200">View</button>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-200">Delete</button>
                    </td>
                </tr>
            `).join('');
            document.querySelector('#files .overflow-x-auto tbody').innerHTML = fileList;
        }
    }

    // Load initial session
    checkSession();

    // Load dashboard data
    async function loadDashboardData() {
        try {
            showLoading();
            // Load dashboard data here (e.g., user stats, prompt counts)
            // For now, we'll just simulate loading
            await new Promise(resolve => setTimeout(resolve, 1000));
            hideLoading();
        } catch (error) {
            showError('Failed to load dashboard data');
        }
    }

    // Load section-specific data
    async function loadSectionData(sectionId) {
        try {
            showLoading();
            switch (sectionId) {
                case 'prompts':
                    await loadPrompts();
                    break;
                case 'files':
                    await loadFiles();
                    break;
                case 'purchases':
                    await loadPurchases();
                    break;
                default:
                    break;
            }
        } catch (error) {
            showError(`Failed to load ${sectionId} data`);
        } finally {
            hideLoading();
        }
    }

    // Load prompts data
    async function loadPrompts() {
        try {
            const prompts = await apiCall('/prompts');
            const promptsList = document.getElementById('promptsList');
            promptsList.innerHTML = prompts.map(prompt => `
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-300">${prompt.title}</td>
                    <td class="px-6 py-4 text-sm text-gray-300">${prompt.category}</td>
                    <td class="px-6 py-4 text-sm text-gray-300">${prompt.status}</td>
                    <td class="px-6 py-4 text-sm">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2 transition duration-200">Edit</button>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-200">Delete</button>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            showError('Failed to load prompts');
        }
    }

    // Load files data
    async function loadFiles() {
        try {
            const files = await apiCall('/file');
            const filesList = document.querySelector('#files .overflow-x-auto tbody');
            filesList.innerHTML = files.map(file => `
            <tr>
                <td class="px-6 py-4 text-sm text-gray-300">${file.id}</td>
                <td class="px-6 py-4 text-sm text-gray-300">${file.name}</td>
                <td class="px-6 py-4 text-sm text-gray-300">${file.type}</td>
                <td class="px-6 py-4 text-sm text-gray-300">${formatFileSize(file.size)}</td>
                <td class="px-6 py-4 text-sm">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2 transition duration-200"
                            onclick="viewFile('${file.id}')">View</button>
                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-200"
                            onclick="deleteFile('${file.id}')">Delete</button>
                </td>
            </tr>
        `).join('');
        } catch (error) {
            showError('Failed to load files');
        }
    }

    // Load purchases data
    async function loadPurchases() {
        try {
            const purchases = await apiCall('/purchases');
            const purchasesList = document.getElementById('purchasesList');
            purchasesList.innerHTML = purchases.map(purchase => `
            <tr>
                <td class="px-6 py-4 text-sm text-gray-300">${purchase.user}</td>
                <td class="px-6 py-4 text-sm text-gray-300">${purchase.item}</td>
                <td class="px-6 py-4 text-sm text-gray-300">$${purchase.amount.toFixed(2)}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 text-xs rounded-full ${getStatusClass(purchase.status)}">
                        ${purchase.status}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-300">${formatDate(purchase.date)}</td>
            </tr>
        `).join('');
        } catch (error) {
            showError('Failed to load purchases');
        }
    }

    // Utility functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getStatusClass(status) {
        const statusClasses = {
            'completed': 'bg-green-500 bg-opacity-20 text-green-400',
            'pending': 'bg-yellow-500 bg-opacity-20 text-yellow-400',
            'failed': 'bg-red-500 bg-opacity-20 text-red-400'
        };
        return statusClasses[status.toLowerCase()] || 'bg-gray-500 bg-opacity-20 text-gray-400';
    }

    // File actions
    async function viewFile(fileId) {
        try {
            const file = await apiCall(`/file/${fileId}`);
            // Handle file viewing based on file type
            if (file.type.startsWith('image/')) {
                // Open image in a modal or new window
                window.open(file.url, '_blank');
            } else {
                // Download the file
                window.location.href = file.url;
            }
        } catch (error) {
            showError('Failed to view file');
        }
    }

    async function deleteFile(fileId) {
        if (!confirm('Are you sure you want to delete this file?')) return;

        try {
            await apiCall(`/file/${fileId}`, {method: 'DELETE'});
            await loadFiles(); // Reload the files list
            alert('File deleted successfully');
        } catch (error) {
            showError('Failed to delete file');
        }
    }

    // Refresh buttons functionality
    document.getElementById('refreshPrompts').addEventListener('click', () => loadPrompts());
    document.getElementById('refreshPurchases').addEventListener('click', () => loadPurchases());


</script>
</body>
</html>


