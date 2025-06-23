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

<!-- Include Login Modal -->
@include('admin.login')

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
            <!-- Include Dashboard Section -->
            @include('admin.dashboard')

            <!-- Include Prompts Section -->
            @include('admin.prompts')

            <!-- Include Files Section -->
            @include('admin.files')

            <!-- Include Purchases Section -->
            @include('admin.purchases')
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

<!-- Include JavaScript -->
@include('admin.scripts')
</body>
</html>


