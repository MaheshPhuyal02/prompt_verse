import React, { useState, useEffect } from 'react';
import { fetchPrompts } from '../api/api';
import { Sparkles, Search, ShoppingCart, Star, Cpu, Brain, Loader2 } from 'lucide-react';
import PromptCard from "../compontents/PromptCard.jsx";

const HomePage = ({ addToCart }) => {
    const [prompts, setPrompts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    const [category, setCategory] = useState('all');

    useEffect(() => {
        const loadPrompts = async () => {
            try {
                const data = await fetchPrompts();
                setPrompts(data);
            } catch (error) {
                console.error('Error loading prompts:', error);
            } finally {
                setLoading(false);
            }
        };

        loadPrompts();
    }, []);

    const filteredPrompts = prompts.filter(prompt => {
        const matchesSearch = prompt.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            prompt.description.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesCategory = category === 'all' || prompt.category === category;
        return matchesSearch && matchesCategory;
    });

    const categories = ['all', 'creative', 'business', 'coding', 'academic'];

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

            {/* Header */}


            <main className="relative z-10 container mx-auto px-4 py-8">
                {/* Category Tabs */}
                <div className="flex overflow-x-auto no-scrollbar mb-8 pb-2">
                    <div className="flex space-x-2">
                        {categories.map((cat) => (
                            <button
                                key={cat}
                                onClick={() => setCategory(cat)}
                                className={`px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors ${
                                    category === cat
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                                }`}
                            >
                                {cat.charAt(0).toUpperCase() + cat.slice(1)}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Featured Banner */}
                <div className="mb-8 bg-gradient-to-r from-indigo-800 to-purple-800 rounded-xl p-6 shadow-lg relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-indigo-500 rounded-full filter blur-3xl opacity-20 transform translate-x-1/4 -translate-y-1/2"></div>
                    <div className="absolute bottom-0 left-0 w-48 h-48 bg-purple-500 rounded-full filter blur-3xl opacity-20 transform -translate-x-1/4 translate-y-1/2"></div>
                    <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div className="space-y-2">
                            <div className="flex items-center space-x-2">
                                <Sparkles className="text-yellow-400" size={20} />
                                <span className="text-yellow-400 font-semibold">Featured Collection</span>
                            </div>
                            <h2 className="text-2xl font-bold">Advanced AI Image Generation</h2>
                            <p className="text-gray-300 max-w-xl">Explore our curated collection of prompts designed to produce stunning AI-generated artwork with incredible detail and creativity.</p>
                            <button className="mt-2 bg-white text-indigo-900 px-6 py-2 rounded-full font-medium hover:bg-opacity-90 transition-colors inline-flex items-center space-x-2">
                                <span>Explore Collection</span>
                            </button>
                        </div>
                        <div className="hidden md:block w-48 h-48 bg-gradient-to-br from-purple-600 to-indigo-800 rounded-lg shadow-2xl"></div>
                    </div>
                </div>

                {/* Prompt Grid */}
                <h2 className="text-2xl font-bold mb-6 flex items-center">
                    <Cpu className="mr-2 text-indigo-400" size={24} />
                    <span>Browse Prompts</span>
                    {!loading && <span className="text-gray-400 text-lg ml-2">({filteredPrompts.length})</span>}
                </h2>

                {loading ? (
                    <div className="flex flex-col items-center justify-center py-16">
                        <Loader2 className="animate-spin text-indigo-500 mb-4" size={48} />
                        <p className="text-gray-400">Loading your prompts...</p>
                    </div>
                ) : filteredPrompts.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredPrompts.map((prompt) => (
                            <PromptCard
                                key={prompt.id}
                                prompt={prompt}
                            />
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-16 bg-gray-800 bg-opacity-50 rounded-lg">
                        <p className="text-gray-400 text-lg">No prompts found matching your criteria.</p>
                        <button
                            onClick={() => {setSearchTerm(''); setCategory('all');}}
                            className="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white"
                        >
                            Reset Filters
                        </button>
                    </div>
                )}
            </main>
        </div>
    );
};


export default HomePage;
