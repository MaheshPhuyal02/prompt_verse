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