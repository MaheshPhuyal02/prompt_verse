import React, { useState, useEffect } from 'react';
import { User, Clock, Calendar, DollarSign, ShoppingBag, Loader, FileText, Tag } from 'lucide-react';

const ProfilePage = () => {
    // Sample user data
    const [user, setUser] = useState({
        id: 'current-user-id',
        name: 'Alex Johnson',
        email: 'alex@example.com',
        joined: 'January 15, 2025',
        profileImage: '/api/placeholder/100/100'
    });

    const [boughtPrompts, setBoughtPrompts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState('purchases');

    useEffect(() => {
        const loadUserPrompts = async () => {
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1200));

                // Mock data
                const data = [
                    {
                        id: 1,
                        title: "GPT-4 Creative Writing Prompt",
                        description: "Generate engaging short stories with complex characters and plots",
                        price: 4.99,
                        purchaseDate: "May 15, 2025",
                        category: "Creative Writing"
                    },
                    {
                        id: 2,
                        title: "Code Optimization Assistant",
                        description: "AI helper for refactoring and optimizing code across multiple languages",
                        price: 9.99,
                        purchaseDate: "May 10, 2025",
                        category: "Development"
                    },
                    {
                        id: 3,
                        title: "Data Analysis Template",
                        description: "Structured prompts for analyzing datasets and generating insights",
                        price: 7.49,
                        purchaseDate: "April 28, 2025",
                        category: "Data Science"
                    }
                ];

                setBoughtPrompts(data);
            } catch (error) {
                console.error('Error loading user prompts:', error);
            } finally {
                setLoading(false);
            }
        };

        loadUserPrompts();
    }, []);

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-900 to-indigo-950 text-gray-100">
            {/* Neural network effect in background */}
            <div className="absolute inset-0 opacity-5 pointer-events-none">
                <svg width="100%" height="100%">
                    <pattern id="neural-net" width="50" height="50" patternUnits="userSpaceOnUse">
                        <path d="M25,0 L50,25 L25,50 L0,25 Z" fill="none" stroke="currentColor" strokeWidth="1"/>
                        <circle cx="25" cy="25" r="3" fill="currentColor"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#neural-net)"/>
                </svg>
            </div>

            {/* Main content */}
            <div className="container mx-auto px-4 py-12 relative z-10">
                <div className="max-w-4xl mx-auto">
                    {/* Profile header */}
                    <div className="bg-gray-800 bg-opacity-70 backdrop-blur-sm rounded-xl shadow-2xl overflow-hidden mb-6">
                        <div className="p-6 sm:p-8">
                            <div className="flex flex-col md:flex-row items-center md:items-start gap-6">
                                <div className="relative">
                                    <div className="w-24 h-24 rounded-full bg-indigo-600 flex items-center justify-center overflow-hidden">
                                        <User size={48} className="text-white" />
                                    </div>
                                </div>

                                <div className="flex-1 text-center md:text-left">
                                    <h1 className="text-3xl font-bold">{user.name}</h1>
                                    <p className="text-indigo-300">{user.email}</p>
                                    <div className="flex flex-wrap gap-4 justify-center md:justify-start mt-3">
                                        <div className="flex items-center text-gray-400">
                                            <Calendar size={16} className="mr-1" />
                                            <span>Joined {user.joined}</span>
                                        </div>
                                        <div className="flex items-center text-gray-400">
                                            <ShoppingBag size={16} className="mr-1" />
                                            <span>{boughtPrompts.length} Prompts</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Tab navigation */}
                    <div className="flex mb-6 bg-gray-800 bg-opacity-60 backdrop-blur-sm rounded-lg overflow-hidden">
                        <button
                            className={`flex-1 py-3 px-4 flex justify-center items-center ${activeTab === 'purchases' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700'}`}
                            onClick={() => setActiveTab('purchases')}
                        >
                            <ShoppingBag size={18} className="mr-2" />
                            Purchase History
                        </button>
                        <button
                            className={`flex-1 py-3 px-4 flex justify-center items-center ${activeTab === 'profile' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700'}`}
                            onClick={() => setActiveTab('profile')}
                        >
                            <User size={18} className="mr-2" />
                            Account Details
                        </button>
                    </div>

                    {/* Content area */}
                    <div className="bg-gray-800 bg-opacity-70 backdrop-blur-sm rounded-xl shadow-2xl overflow-hidden">
                        <div className="p-6 sm:p-8">
                            {loading ? (
                                <div className="flex flex-col items-center justify-center py-12">
                                    <Loader size={36} className="animate-spin text-indigo-400 mb-4" />
                                    <p className="text-gray-400">Loading your profile data...</p>
                                </div>
                            ) : activeTab === 'purchases' ? (
                                <>
                                    <h2 className="text-2xl font-semibold mb-6 flex items-center">
                                        <ShoppingBag className="mr-3" />
                                        Purchase History
                                    </h2>

                                    {boughtPrompts.length === 0 ? (
                                        <div className="text-center py-12 bg-gray-900 bg-opacity-50 rounded-lg">
                                            <ShoppingBag size={48} className="mx-auto text-gray-500 mb-4" />
                                            <p className="text-xl text-gray-400">No prompts purchased yet</p>
                                            <p className="text-gray-500 mt-2">Browse our marketplace to find AI assistants that can help with your tasks.</p>
                                        </div>
                                    ) : (
                                        <div className="space-y-4 divide-y divide-gray-700">
                                            {boughtPrompts.map((prompt) => (
                                                <div key={prompt.id} className="pt-4 first:pt-0 pb-4 last:pb-0">
                                                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                        <div>
                                                            <h3 className="text-xl font-medium text-white">{prompt.title}</h3>
                                                            <p className="text-gray-400 mt-1">{prompt.description}</p>
                                                            <div className="flex flex-wrap items-center gap-4 mt-2">
                                                                <div className="flex items-center text-indigo-300">
                                                                    <Calendar size={16} className="mr-1" />
                                                                    <span className="text-sm">{prompt.purchaseDate}</span>
                                                                </div>
                                                                <div className="flex items-center text-green-400">
                                                                    <DollarSign size={16} className="mr-1" />
                                                                    <span className="text-sm">${prompt.price.toFixed(2)}</span>
                                                                </div>
                                                                <div className="flex items-center text-purple-300">
                                                                    <Tag size={16} className="mr-1" />
                                                                    <span className="text-sm">{prompt.category}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div className="flex shrink-0">
                                                            <button className="py-2 px-4 bg-indigo-900 hover:bg-indigo-800 rounded-lg text-indigo-200 flex items-center">
                                                                <FileText size={16} className="mr-2" />
                                                                <span>Use Prompt</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </>
                            ) : (
                                <>
                                    <h2 className="text-2xl font-semibold mb-6 flex items-center">
                                        <User className="mr-3" />
                                        Account Details
                                    </h2>

                                    <div className="bg-gray-900 bg-opacity-50 rounded-lg p-6 space-y-4">
                                        <div>
                                            <label className="block text-gray-400 text-sm mb-1">Name</label>
                                            <div className="text-lg">{user.name}</div>
                                        </div>

                                        <div>
                                            <label className="block text-gray-400 text-sm mb-1">Email</label>
                                            <div className="text-lg">{user.email}</div>
                                        </div>

                                        <div>
                                            <label className="block text-gray-400 text-sm mb-1">Member Since</label>
                                            <div className="text-lg">{user.joined}</div>
                                        </div>

                                        <div className="pt-4">
                                            <button className="py-2 px-4 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white">
                                                Edit Profile
                                            </button>
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ProfilePage;
