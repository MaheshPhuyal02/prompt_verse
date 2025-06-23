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
                    <p class="text-3xl font-bold text-blue-400" id="totalUsersStat">0</p>
                </div>
                <i class="fas fa-users text-3xl text-blue-400 opacity-50"></i>
            </div>
        </div>
        <div
            class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 hover:transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Active Prompts</p>
                    <p class="text-3xl font-bold text-purple-400" id="activePromptsStat">0</p>
                </div>
                <i class="fas fa-lightbulb text-3xl text-purple-400 opacity-50"></i>
            </div>
        </div>
        <div
            class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 hover:transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Files Uploaded</p>
                    <p class="text-3xl font-bold text-green-400" id="filesUploadedStat">0</p>
                </div>
                <i class="fas fa-file-upload text-3xl text-green-400 opacity-50"></i>
            </div>
        </div>
        <div
            class="bg-gray-800 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-gray-700 hover:transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Revenue</p>
                    <p class="text-3xl font-bold text-yellow-400" id="totalRevenueStat">$0</p>
                </div>
                <i class="fas fa-dollar-sign text-3xl text-yellow-400 opacity-50"></i>
            </div>
        </div>
    </div>
</section> 