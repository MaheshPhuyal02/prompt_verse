<script>
    // Configuration
    const API_URL = 'http://127.0.0.1:8000/api';
    let authToken = null;

    // Enhanced logging function
    const log = (message, data = null, type = 'info') => {
        const timestamp = new Date().toISOString();
        const logMessage = `[${timestamp}] [${type.toUpperCase()}] ${message}`;
        console.log(logMessage, data || '');

        // Also log to a visible area if needed
        if (type === 'error') {
            console.error(logMessage, data || '');
        }
    };

    // Check for existing session
    const checkSession = () => {
        log('Checking for existing session');
        const token = sessionStorage.getItem('auth_token');
        if (token) {
            log('Found existing token, showing main app');
            authToken = token;
            showMainApp();
        } else {
            log('No token found, showing login modal');
            showLoginModal();
        }
    };

    // Show/Hide functions
    const showLoginModal = () => {
        log('Showing login modal');
        document.getElementById('loginModal').classList.remove('hidden');
        document.getElementById('mainApp').classList.add('hidden');
    };

    const showMainApp = () => {
        log('Showing main app');
        document.getElementById('loginModal').classList.add('hidden');
        document.getElementById('mainApp').classList.remove('hidden');
        loadDashboardData();
    };

    const showLoading = () => {
        log('Showing loading overlay');
        document.getElementById('loadingOverlay').classList.remove('hidden');
    };

    const hideLoading = () => {
        log('Hiding loading overlay');
        document.getElementById('loadingOverlay').classList.add('hidden');
    };

    const showError = (message, containerId = 'loginError') => {
        log('Showing error message', message, 'error');
        const errorElement = document.getElementById(containerId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        setTimeout(() => errorElement.classList.add('hidden'), 5000);
    };

    // Enhanced API Helper with comprehensive logging
    const apiCall = async (endpoint, options = {}, data = {}) => {
        const requestId = Math.random().toString(36).substr(2, 9);
        const url = `${API_URL}${endpoint}`;

        log(`[${requestId}] Making API call to: ${url}`, {
            method: options.method || 'GET',
            headers: options.headers,
            data: data
        });

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
            log(`[${requestId}] Using auth token: ${authToken.substring(0, 20)}...`);
        }

        if (data && Object.keys(data).length > 0) {
            config.body = data;
        }

        try {
            log(`[${requestId}] Sending request...`);
            const response = await fetch(url, config);
            const responseData = await response.json();

            log(`[${requestId}] Response received`, {
                status: response.status,
                statusText: response.statusText,
                data: responseData
            });

            if (!response.ok) {
                log(`[${requestId}] API request failed`, {
                    status: response.status,
                    error: responseData
                }, 'error');
                throw new Error(responseData.message || 'API request failed');
            }

            log(`[${requestId}] API call successful`);
            return responseData;
        } catch (error) {
            log(`[${requestId}] API Error occurred`, error, 'error');
            throw error;
        }
    };

    // Login functionality
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        log('Login form submitted');

        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;

        log('Attempting login', { email: email });

        showLoading();

        try {
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);

            log('Sending login request');
            const response = await fetch(`${API_URL}/login`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            log('Login response received', data);

            if (response.ok && data.apiKey) {
                log('Login successful, storing token');
                authToken = data.apiKey;
                sessionStorage.setItem('auth_token', authToken);
                showMainApp();
            } else {
                log('Login failed', data, 'error');
                showError(data.message || 'Login failed');
            }
        } catch (error) {
            log('Login network error', error, 'error');
            showError('Network error. Please try again.');
        } finally {
            hideLoading();
        }
    });

    // Logout functionality
    document.getElementById('logoutBtn').addEventListener('click', () => {
        log('Logout button clicked');
        authToken = null;
        sessionStorage.removeItem('auth_token');
        showLoginModal();
    });

    // Navigation
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const sectionId = e.target.dataset.section;
            log(`Navigation clicked: ${sectionId}`);

            // Remove active class from all buttons
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active', 'bg-blue-600', 'bg-opacity-50'));
            // Add active class to clicked button
            e.target.classList.add('active', 'bg-blue-600', 'bg-opacity-50');

            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => section.classList.add('hidden'));
            // Show selected section
            document.getElementById(sectionId).classList.remove('hidden');

            // Load section-specific data
            loadSectionData(sectionId);
        });
    });

    // Prompt form submission
    document.getElementById('promptForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        log('Prompt form submitted');

        const title = document.getElementById('promptTitle').value;
        const price = document.getElementById('promptPrice').value;
        const content = document.getElementById('promptContent').value;
        const category = document.getElementById('promptCategory').value;

        log('Prompt form data', { title, price, content, category });

        showLoading();

        try {
            if (!title || !content || !category) {
                throw new Error('All fields are required');
            }

            const fileInput = document.getElementById('promptImage');
            const file = fileInput.files[0];
            log("File upload attempt", { fileName: file?.name, fileSize: file?.size });

            const uploadForm = new FormData()
            uploadForm.append("file", file, Math.random().toString(4));

            log('Uploading file...');
            const uploadFile = await apiCall("/file/upload", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: uploadForm
            });

            if (uploadFile.file_id) {
                log('File uploaded successfully', uploadFile);

                const formData = new FormData();
                formData.append('title', title);
                formData.append('description', content);
                formData.append('category', category);
                formData.append('image', uploadFile.file_id);
                formData.append('popular', 1);
                formData.append('price', price);

                log('Creating prompt...');
                const req = await apiCall('/prompts', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (req) {
                    log('Prompt created successfully', req);
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
            log('Failed to create prompt', error, 'error');
            alert('Failed to create prompt: ' + error.message);
        } finally {
            hideLoading();
        }
    });

    // Image upload handling with logging
    const imageDropZone = document.getElementById('imageDropZone');
    const promptImage = document.getElementById('promptImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    imageDropZone.addEventListener('click', () => {
        log('Image drop zone clicked');
        promptImage.click();
    });

    promptImage.addEventListener('change', () => {
        log('Image file selected');
        handleImageSelect();
    });

    imageDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageDropZone.classList.add('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });

    imageDropZone.addEventListener('dragleave', () => {
        imageDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });

    imageDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        log('Image dropped on drop zone');
        imageDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');

        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            log('Valid image file dropped', { fileName: files[0].name, fileType: files[0].type });
            promptImage.files = files;
            handleImageSelect();
        } else {
            log('Invalid file dropped', { fileType: files[0]?.type }, 'error');
        }
    });

    function handleImageSelect() {
        const file = promptImage.files[0];
        if (file) {
            log('Processing selected image', { fileName: file.name, fileSize: file.size, fileType: file.type });
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                log('Image preview loaded');
            };
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('removeImage').addEventListener('click', () => {
        log('Remove image button clicked');
        promptImage.value = '';
        imagePreview.classList.add('hidden');
    });

    // File upload handling with logging
    const fileDropZone = document.getElementById('fileDropZone');
    const fileUpload = document.getElementById('fileUpload');

    fileDropZone.addEventListener('click', () => {
        log('File drop zone clicked');
        fileUpload.click();
    });

    fileUpload.addEventListener('change', () => {
        log('File selected for upload');
        handleFileSelect();
    });

    fileDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileDropZone.classList.add('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });

    fileDropZone.addEventListener('dragleave', () => {
        fileDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');
    });

    fileDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        log('File dropped on drop zone');
        fileDropZone.classList.remove('border-blue-500', 'bg-blue-500', 'bg-opacity-20');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            log('Files dropped', { fileCount: files.length, fileNames: Array.from(files).map(f => f.name) });
            fileUpload.files = files;
            handleFileSelect();
        }
    });

    function handleFileSelect() {
        const files = fileUpload.files;
        if (files.length > 0) {
            log('Processing selected files', { fileCount: files.length, fileNames: Array.from(files).map(f => f.name) });
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
            log('File list updated in UI');
        }
    }

    // Load initial session
    checkSession();

    // Load dashboard data
    async function loadDashboardData() {
        log('Loading dashboard data');
        try {
            showLoading();
            const stats = await apiCall('/dashboard-stats');
            log('Dashboard stats received', stats);

            document.getElementById('totalUsersStat').textContent = stats.total_users;
            document.getElementById('activePromptsStat').textContent = stats.active_prompts;
            document.getElementById('filesUploadedStat').textContent = stats.files_uploaded;
            document.getElementById('totalRevenueStat').textContent = `$${stats.total_revenue}`;

            hideLoading();
            log('Dashboard data loaded successfully');
        } catch (error) {
            log('Failed to load dashboard data', error, 'error');
            showError('Failed to load dashboard data');
            hideLoading();
        }
    }

    // Load section-specific data
    async function loadSectionData(sectionId) {
        log(`Loading section data for: ${sectionId}`);
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
                    log(`No specific loader for section: ${sectionId}`);
                    break;
            }
        } catch (error) {
            log(`Failed to load ${sectionId} data`, error, 'error');
            showError(`Failed to load ${sectionId} data`);
        } finally {
            hideLoading();
        }
    }

    // Delete prompt
    async function deletePrompt(promptId) {
        log(`Attempting to delete prompt: ${promptId}`);
        if (!confirm('Are you sure you want to delete this prompt?')) {
            log('Prompt deletion cancelled by user');
            return;
        }

        try {
            await apiCall(`/prompts/${promptId}`, {method: 'DELETE'});
            log('Prompt deleted successfully');
            await loadPrompts();
            alert('Prompt deleted successfully');
        } catch (error) {
            log('Failed to delete prompt', error, 'error');
            showError('Failed to delete prompt');
        }
    }

    // Load prompts data - Fixed to handle correct data structure
    async function loadPrompts() {
        log('Loading prompts data');
        try {
            const prompts = await apiCall('/prompts');
            log('Prompts API response received', prompts);

            const promptsList = document.getElementById('promptsList');
            if (!promptsList) {
                log('Prompts list element not found', 'error');
                return;
            }

            if (!Array.isArray(prompts)) {
                log('Prompts response is not an array', prompts, 'error');
                promptsList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-sm text-gray-300 text-center">No prompts found or invalid data format</td></tr>';
                return;
            }

            if (prompts.length === 0) {
                log('No prompts found');
                promptsList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-sm text-gray-300 text-center">No prompts available</td></tr>';
                return;
            }

            const promptsHtml = prompts.map(prompt => {
                log('Processing prompt', prompt);
                return `
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-300">${prompt.title || 'No Title'}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">${prompt.category || 'Uncategorized'}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">${prompt.popular ? 'Popular' : 'Standard'}</td>
                        <td class="px-6 py-4 text-sm">
                            <button onclick="deletePrompt(${prompt.id})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-200">Delete</button>
                        </td>
                    </tr>
                `;
            }).join('');

            promptsList.innerHTML = promptsHtml;
            log(`Successfully loaded ${prompts.length} prompts`);
        } catch (error) {
            log('Failed to load prompts', error, 'error');
            const promptsList = document.getElementById('promptsList');
            if (promptsList) {
                promptsList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-sm text-red-400 text-center">Failed to load prompts</td></tr>';
            }
            showError('Failed to load prompts');
        }
    }

    // Load files data
    async function loadFiles() {
        log('Loading files data');
        try {
            const files = await apiCall('/file');
            log('Files API response received', files);

            const filesList = document.querySelector('#files .overflow-x-auto tbody');
            if (!filesList) {
                log('Files list element not found', 'error');
                return;
            }

            if (!Array.isArray(files)) {
                log('Files response is not an array', files, 'error');
                filesList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-sm text-gray-300 text-center">No files found or invalid data format</td></tr>';
                return;
            }

            if (files.length === 0) {
                log('No files found');
                filesList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-sm text-gray-300 text-center">No files available</td></tr>';
                return;
            }

            const filesHtml = files.map(file => {
                log('Processing file', file);
                return `
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-300">${file.id || 'N/A'}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">${file.name || 'Unknown'}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">${file.type || 'Unknown'}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">${formatFileSize(file.size || 0)}</td>
                        <td class="px-6 py-4 text-sm">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2 transition duration-200"
                                    onclick="viewFile('${file.id}')">View</button>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-200"
                                    onclick="deleteFile('${file.id}')">Delete</button>
                        </td>
                    </tr>
                `;
            }).join('');

            filesList.innerHTML = filesHtml;
            log(`Successfully loaded ${files.length} files`);
        } catch (error) {
            log('Failed to load files', error, 'error');
            const filesList = document.querySelector('#files .overflow-x-auto tbody');
            if (filesList) {
                filesList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-sm text-red-400 text-center">Failed to load files</td></tr>';
            }
            showError('Failed to load files');
        }
    }

    // Load purchases data
    async function loadPurchases() {
        log('Loading purchases data');
        showLoading();
        try {
            const purchases = await apiCall('/admin/purchases');
            log('Purchases API response received', purchases);

            const purchasesList = document.getElementById('purchasesList');
            if (!purchasesList) {
                log('Purchases list element not found', 'error');
                return;
            }

            if (!Array.isArray(purchases)) {
                log('Purchases response is not an array', purchases, 'error');
                purchasesList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-sm text-gray-300 text-center">No purchases found or invalid data format</td></tr>';
                return;
            }

            if (purchases.length === 0) {
                log('No purchases found');
                purchasesList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-sm text-gray-300 text-center">No purchases available</td></tr>';
                return;
            }

            const purchasesHtml = purchases.map(purchase => {
                log('Processing purchase', purchase);
                return `
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-300">${purchase.user || 'Unknown User'}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">${purchase.item || 'Unknown Item'}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">$${(purchase.amount || 0)}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full ${getStatusClass(purchase.status)}">
                                ${purchase.status || 'Unknown'}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300">${formatDate(purchase.date || new Date())}</td>
                    </tr>
                `;
            }).join('');

            purchasesList.innerHTML = purchasesHtml;
            log(`Successfully loaded ${purchases.length} purchases`);
        } catch (error) {
            log('Failed to load purchases', error, 'error');
            const purchasesList = document.getElementById('purchasesList');
            if (purchasesList) {
                purchasesList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-sm text-red-400 text-center">Failed to load purchases</td></tr>';
            }
            showError('Failed to load purchases');
        } finally {
            hideLoading();
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
        log(`Attempting to view file: ${fileId}`);
        try {
            const file = await apiCall(`/file/${fileId}`);
            log('File data received for viewing', file);

            if (file.type && file.type.startsWith('image/')) {
                log('Opening image file in new tab');
                window.open(file.url, '_blank');
            } else {
                log('Opening file for download');
                window.location.href = file.url;
            }
        } catch (error) {
            log('Failed to view file', error, 'error');
            showError('Failed to view file');
        }
    }

    async function deleteFile(fileId) {
        log(`Attempting to delete file: ${fileId}`);
        if (!confirm('Are you sure you want to delete this file?')) {
            log('File deletion cancelled by user');
            return;
        }

        try {
            await apiCall(`/file/${fileId}`, {method: 'DELETE'});
            log('File deleted successfully');
            await loadFiles();
            alert('File deleted successfully');
        } catch (error) {
            log('Failed to delete file', error, 'error');
            showError('Failed to delete file');
        }
    }

    // Refresh buttons functionality
    document.getElementById('refreshPrompts').addEventListener('click', () => {
        log('Refresh prompts button clicked');
        loadPrompts();
    });

    document.getElementById('refreshPurchases').addEventListener('click', () => {
        log('Refresh purchases button clicked');
        loadPurchases();
    });

    // Initialize logging
    log('Admin panel scripts loaded successfully');
</script>
