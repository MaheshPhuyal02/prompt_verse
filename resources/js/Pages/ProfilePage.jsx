import React, { useState, useEffect } from 'react';
import { User, Clock, Calendar, DollarSign, ShoppingBag, Loader, FileText, Tag, X, Copy, Check, ExternalLink } from 'lucide-react';
import {useAuth} from "../api/auth_provider.jsx";
import { getPurchasedPrompts } from "../api/api.js";

const ProfilePage = () => {
    // Sample user data
    const [user, setUser] = useState({

    });
    const { profile } = useAuth();

    const [boughtPrompts, setBoughtPrompts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState('purchases');
    const [selectedPrompt, setSelectedPrompt] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [copied, setCopied] = useState(false);

    const handleCopyPrompt = async (text) => {
        try {
            await navigator.clipboard.writeText(text);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setSelectedPrompt(null);
        setCopied(false);
    };

    // Handle escape key to close modal
    useEffect(() => {
        const handleEscape = (e) => {
            if (e.key === 'Escape' && isModalOpen) {
                closeModal();
            }
        };

        if (isModalOpen) {
            document.addEventListener('keydown', handleEscape);
            document.body.style.overflow = 'hidden';
        }

        return () => {
            document.removeEventListener('keydown', handleEscape);
            document.body.style.overflow = 'unset';
        };
    }, [isModalOpen]);

    useEffect( () => {
        profile()
            .then((response) => {
                console
                    .log("Profile response:", response);
                setUser(response.user);
            })
            .catch((error) => {
                console.error("Error fetching profile:", error);
            });

        const loadUserPrompts = async () => {
            try {
                const data = await getPurchasedPrompts();
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
                                    {/*<div className="flex flex-wrap gap-4 justify-center md:justify-start mt-3">*/}
                                    {/*    <div className="flex items-center text-gray-400">*/}
                                    {/*        <Calendar size={16} className="mr-1" />*/}
                                    {/*        <span>Joined {user.updated_at}</span>*/}
                                    {/*    </div>*/}
                                    {/*    <div className="flex items-center text-gray-400">*/}
                                    {/*        <ShoppingBag size={16} className="mr-1" />*/}
                                    {/*        <span>{boughtPrompts.length} Prompts</span>*/}
                                    {/*    </div>*/}
                                    {/*</div>*/}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Tab navigation */}
                    <div className="flex mb-6 bg-gray-800 bg-opacity-60 backdrop-blur-sm rounded-lg overflow-hidden">
                        <button
                            className={`flex-1 py-3 px-4 flex justify-center items-center transition-colors ${activeTab === 'purchases' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700'}`}
                            onClick={() => setActiveTab('purchases')}
                        >
                            <ShoppingBag size={18} className="mr-2" />
                            Purchase History
                        </button>
                        <button
                            className={`flex-1 py-3 px-4 flex justify-center items-center transition-colors ${activeTab === 'profile' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700'}`}
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
                                                <div
                                                    key={prompt.id}
                                                    className="pt-4 first:pt-0 pb-4 last:pb-0 cursor-pointer hover:bg-gray-900/40 rounded-lg transition"
                                                    onClick={() => {
                                                        setSelectedPrompt(prompt);
                                                        setIsModalOpen(true);
                                                    }}
                                                >
                                                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                        {/* Prompt image thumbnail */}
                                                        <img
                                                            src={`http://127.0.0.1:8000/api/file/${prompt.image}`}
                                                            alt={prompt.title}
                                                            className="w-20 h-20 object-cover rounded-lg border border-gray-700 mr-4 mb-2 md:mb-0"
                                                            style={{ minWidth: '5rem' }}
                                                        />
                                                        <div>
                                                            <h3 className="text-xl font-medium text-white">{prompt.title}</h3>
                                                            <p className="text-gray-400 mt-1 line-clamp-2">{prompt.description}</p>
                                                            <div className="flex flex-wrap items-center gap-4 mt-2">
                                                                <div className="flex items-center text-indigo-300">
                                                                    <Calendar size={16} className="mr-1" />
                                                                    <span className="text-sm">{prompt.purchaseDate}</span>
                                                                </div>
                                                                <div className="flex items-center text-green-400">
                                                                    <DollarSign size={16} className="mr-1" />
                                                                    <span className="text-sm">Rs{prompt.price}</span>
                                                                </div>
                                                                <div className="flex items-center text-purple-300">
                                                                    <Tag size={16} className="mr-1" />
                                                                    <span className="text-sm">{prompt.category}</span>
                                                                </div>
                                                            </div>
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

            {/* Enhanced Modal for prompt details */}
            {isModalOpen && selectedPrompt && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
                    onClick={closeModal}
                >
                    <div
                        className="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col border border-gray-700/50 animate-in fade-in-0 zoom-in-95 duration-200"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Header */}
                        <div className="flex items-center justify-between p-6 border-b border-gray-700/50">
                            <div className="flex items-center space-x-3">
                                {/* Prompt image in modal */}
                                <img
                                    src={`http://127.0.0.1:8000/api/file/${selectedPrompt.image}`}
                                    alt={selectedPrompt.title}
                                    className="w-16 h-16 object-cover rounded-lg border border-gray-700 mr-4"
                                    style={{ minWidth: '4rem' }}
                                />
                                <div>
                                    <h2 className="text-xl font-bold text-white">{selectedPrompt.title}</h2>
                                    <p className="text-sm text-gray-400">{selectedPrompt.category}</p>
                                </div>
                            </div>
                            <button
                                className="p-2 rounded-lg hover:bg-gray-700/50 text-gray-400 hover:text-white transition-colors"
                                onClick={closeModal}
                                aria-label="Close dialog"
                            >
                                <X size={20} />
                            </button>
                        </div>

                        {/* Content */}
                        <div className="flex-1 overflow-y-auto p-6 space-y-6">
                            {/* Metadata */}
                            <div className="flex flex-wrap gap-4">
                                <div className="flex items-center px-3 py-2 rounded-lg bg-indigo-900/30 text-indigo-300">
                                    <Calendar size={16} className="mr-2" />
                                    <span className="text-sm">Purchased {selectedPrompt.purchaseDate}</span>
                                </div>
                                <div className="flex items-center px-3 py-2 rounded-lg bg-green-900/30 text-green-400">
                                    <DollarSign size={16} className="mr-2" />
                                    <span className="text-sm">Rs{selectedPrompt.price}</span>
                                </div>
                                <div className="flex items-center px-3 py-2 rounded-lg bg-purple-900/30 text-purple-300">
                                    <Tag size={16} className="mr-2" />
                                    <span className="text-sm">{selectedPrompt.category}</span>
                                </div>
                            </div>

                            {/* Description */}
                            <div>
                                <div className="flex items-center justify-between mb-3">
                                    <h3 className="text-lg font-semibold text-white">Prompt Description</h3>
                                    <button
                                        className={`flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all ${
                                            copied
                                                ? 'bg-green-600 text-white'
                                                : 'bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white'
                                        }`}
                                        onClick={() => handleCopyPrompt(selectedPrompt.description)}
                                    >
                                        {copied ? (
                                            <>
                                                <Check size={16} className="mr-2" />
                                                Copied!
                                            </>
                                        ) : (
                                            <>
                                                <Copy size={16} className="mr-2" />
                                                Copy Prompt
                                            </>
                                        )}
                                    </button>
                                </div>
                                <div className="bg-gray-800/50 rounded-lg p-4 border border-gray-700/30">
                                    <p className="text-gray-300 leading-relaxed whitespace-pre-line">
                                        {selectedPrompt.description}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Footer */}
                        <div className="flex items-center justify-between p-6 border-t border-gray-700/50 bg-gray-900/30">
                            <div className="text-sm text-gray-400">
                                Press <kbd className="px-2 py-1 bg-gray-700 rounded text-xs">Esc</kbd> to close
                            </div>
                            <div className="flex space-x-3">
                                <button
                                    className="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white transition-colors"
                                    onClick={closeModal}
                                >
                                    Close
                                </button>
                                <button
                                    className="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors flex items-center"
                                    onClick={() => {
                                        // Handle use prompt action
                                        console.log('Using prompt:', selectedPrompt.title);
                                    }}
                                >
                                    <ExternalLink size={16} className="mr-2" />
                                    Use Prompt
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default ProfilePage;
