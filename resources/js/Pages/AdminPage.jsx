import React, { useState } from 'react';
import { addPrompt } from '../api/api';

const AdminPage = () => {
    const [promptData, setPromptData] = useState({
        title: '',
        description: '',
        price: '',
        image: '',
    });

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await addPrompt(promptData);
            alert('Prompt added successfully!');
            setPromptData({ title: '', description: '', price: '', image: '' });
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

    return (
        <div className="container mx-auto px-4 py-8">
            <h1 className="text-3xl font-bold mb-8">Admin Dashboard</h1>
            <div className="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
                <h2 className="text-2xl font-semibold mb-4">Add New Prompt</h2>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-sm font-medium mb-1">Title</label>
                        <input
                            type="text"
                            name="title"
                            value={promptData.title}
                            onChange={handleChange}
                            className="w-full border rounded-md px-3 py-2"
                            required
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium mb-1">Description</label>
                        <textarea
                            name="description"
                            value={promptData.description}
                            onChange={handleChange}
                            className="w-full border rounded-md px-3 py-2"
                            rows="4"
                            required
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium mb-1">Price</label>
                        <input
                            type="number"
                            name="price"
                            value={promptData.price}
                            onChange={handleChange}
                            className="w-full border rounded-md px-3 py-2"
                            step="0.01"
                            required
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium mb-1">Image URL</label>
                        <input
                            type="url"
                            name="image"
                            value={promptData.image}
                            onChange={handleChange}
                            className="w-full border rounded-md px-3 py-2"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        className="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600"
                    >
                        Add Prompt
                    </button>
                </form>
            </div>
        </div>
    );
};

export default AdminPage;
