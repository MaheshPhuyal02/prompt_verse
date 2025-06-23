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
            <input type="file" id="fileUpload" class="hidden">
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