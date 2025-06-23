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