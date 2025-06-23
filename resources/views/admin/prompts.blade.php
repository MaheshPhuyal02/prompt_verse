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
                    <label class="block text-sm font-medium text-gray-300 mb-2">Prompt Price</label>
                    <input type="number" id="promptPrice" required
                           class="w-full px-4 py-3 bg-gray-700 bg-opacity-50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                           placeholder="Enter prompt price.">
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