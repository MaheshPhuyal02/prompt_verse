import React, { useState } from 'react';
import { Upload, Plus, Eye, ShoppingCart, Edit, Trash2, DollarSign, Users, Package } from 'lucide-react';

const AdminPage = () => {
    const [activeTab, setActiveTab] = useState('add');
    const [promptData, setPromptData] = useState({
        title: '',
        description: '',
        price: '',
        image: null,
        category: '',
        tags: ''
    });

    // Sample prompts data for gallery
    const [prompts, setPrompts] = useState([
        {
            id: 1,
            title: "AI Art Generation Prompt",
            description: "Create stunning digital artwork with detailed prompts for Midjourney, DALL-E, and Stable Diffusion",
            price: 299,
            image: "/api/placeholder/300/200",
            category: "Art & Design",
            tags: ["ai", "art", "creative"],
            sales: 45
        },
        {
            id: 2,
            title: "Business Email Templates",
            description: "Professional email templates for various business scenarios including sales, follow-ups, and networking",
            price: 199,
            image: "/api/placeholder/300/200",
            category: "Business",
            tags: ["email", "business", "professional"],
            sales: 78
        },
        {
            id: 3,
            title: "Social Media Content Ideas",
            description: "Engaging social media post ideas and templates for Instagram, Twitter, and LinkedIn",
            price: 149,
            image: "/api/placeholder/300/200",
            category: "Marketing",
            tags: ["social", "marketing", "content"],
            sales: 123
        }
    ]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            // Simulate API call
            const newPrompt = {
                id: prompts.length + 1,
                ...promptData,
                price: parseFloat(promptData.price),
                tags: promptData.tags.split(',').map(tag => tag.trim()),
                sales: 0
            };
            setPrompts([...prompts, newPrompt]);
            alert('Prompt added successfully!');
            setPromptData({ title: '', description: '', price: '', image: null, category: '', tags: '' });
        } catch (error) {
            console.error('Error adding prompt:', error);
            alert('Failed to add prompt');
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setPromptData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setPromptData(prev => ({
                ...prev,
                image: URL.createObjectURL(file)
            }));
        }
    };

    const handleBuy = (promptId) => {
        alert(`Purchasing prompt ${promptId}. Redirecting to payment...`);
    };

    const handleDelete = (promptId) => {
        if (window.confirm('Are you sure you want to delete this prompt?')) {
            setPrompts(prompts.filter(p => p.id !== promptId));
        }
    };

    const stats = {
        totalPrompts: prompts.length,
        totalSales: prompts.reduce((sum, p) => sum + p.sales, 0),
        totalRevenue: prompts.reduce((sum, p) => sum + (p.sales * p.price), 0)
    };

    return (
        <div className="min-h-screen bg-gray-100">
            {/* Header */}
            <div className="bg-white shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center py-6">
                        <h1 className="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                        <div className="flex space-x-4">
                            <button
                                onClick={() => setActiveTab('add')}
                                className={`px-4 py-2 rounded-md ${activeTab === 'add' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`}
                            >
                                <Plus className="w-4 h-4 inline mr-2" />
                                Add Prompt
                            </button>
                            <button
                                onClick={() => setActiveTab('gallery')}
                                className={`px-4 py-2 rounded-md ${activeTab === 'gallery' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`}
                            >
                                <Eye className="w-4 h-4 inline mr-2" />
                                Gallery
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {/* Stats Dashboard */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <Package className="w-8 h-8 text-blue-500" />
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-500">Total Prompts</p>
                                <p className="text-2xl font-bold text-gray-900">{stats.totalPrompts}</p>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <Users className="w-8 h-8 text-green-500" />
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-500">Total Sales</p>
                                <p className="text-2xl font-bold text-gray-900">{stats.totalSales}</p>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <DollarSign className="w-8 h-8 text-purple-500" />
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p className="text-2xl font-bold text-gray-900">₹{stats.totalRevenue}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Add Prompt Form */}
                {activeTab === 'add' && (
                    <div className="bg-white rounded-lg shadow p-6">
                        <h2 className="text-2xl font-semibold mb-6">Add New Prompt</h2>
                        <div className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value={promptData.title}
                                        onChange={handleChange}
                                        className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <select
                                        name="category"
                                        value={promptData.category}
                                        onChange={handleChange}
                                        className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    >
                                        <option value="">Select Category</option>
                                        <option value="Art & Design">Art & Design</option>
                                        <option value="Business">Business</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Writing">Writing</option>
                                        <option value="Technology">Technology</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea
                                    name="description"
                                    value={promptData.description}
                                    onChange={handleChange}
                                    className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    rows="4"
                                    required
                                />
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Price (₹)</label>
                                    <input
                                        type="number"
                                        name="price"
                                        value={promptData.price}
                                        onChange={handleChange}
                                        className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        step="1"
                                        min="1"
                                        required
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Tags (comma separated)</label>
                                    <input
                                        type="text"
                                        name="tags"
                                        value={promptData.tags}
                                        onChange={handleChange}
                                        className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="ai, creative, business"
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">Image</label>
                                <div className="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div className="space-y-1 text-center">
                                        {promptData.image ? (
                                            <div>
                                                <img src={promptData.image} alt="Preview" className="mx-auto h-32 w-32 object-cover rounded-md" />
                                                <p className="text-sm text-gray-500 mt-2">Image selected</p>
                                            </div>
                                        ) : (
                                            <>
                                                <Upload className="mx-auto h-12 w-12 text-gray-400" />
                                                <div className="flex text-sm text-gray-600">
                                                    <label className="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                        <span>Upload a file</span>
                                                        <input
                                                            type="file"
                                                            accept="image/*"
                                                            onChange={handleFileChange}
                                                            className="sr-only"
                                                            required
                                                        />
                                                    </label>
                                                    <p className="pl-1">or drag and drop</p>
                                                </div>
                                                <p className="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </>
                                        )}
                                    </div>
                                </div>
                            </div>

                            <button
                                type="submit"
                                className="w-full bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition duration-200 font-medium"
                            >
                                Add Prompt
                            </button>
                        </div>
                    </div>
                )}

                {/* Prompt Gallery */}
                {activeTab === 'gallery' && (
                    <div>
                        <div className="flex justify-between items-center mb-6">
                            <h2 className="text-2xl font-semibold">Prompt Gallery</h2>
                            <p className="text-gray-600">{prompts.length} prompts available</p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {prompts.map((prompt) => (
                                <div key={prompt.id} className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                                    <img
                                        src={prompt.image}
                                        alt={prompt.title}
                                        className="w-full h-48 object-cover"
                                    />
                                    <div className="p-6">
                                        <div className="flex justify-between items-start mb-2">
                                            <h3 className="text-lg font-semibold text-gray-900 truncate">{prompt.title}</h3>
                                            <span className="text-lg font-bold text-green-600">₹{prompt.price}</span>
                                        </div>
                                        <p className="text-gray-600 text-sm mb-3 line-clamp-2">{prompt.description}</p>
                                        <div className="flex flex-wrap gap-1 mb-3">
                                            {prompt.tags.map((tag, index) => (
                                                <span key={index} className="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md">
                                                    {tag}
                                                </span>
                                            ))}
                                        </div>
                                        <div className="flex justify-between items-center mb-4">
                                            <span className="text-sm text-gray-500">{prompt.sales} sales</span>
                                            <span className="text-sm text-gray-500">{prompt.category}</span>
                                        </div>
                                        <div className="flex space-x-2">

                                            <button className="px-3 py-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition duration-200">
                                                <Edit className="w-4 h-4" />
                                            </button>
                                            <button
                                                onClick={() => handleDelete(prompt.id)}
                                                className="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition duration-200"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default AdminPage;
