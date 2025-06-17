import React, { useState, useEffect } from 'react';
import { CreditCard, ArrowLeft, Plus, MapPin } from 'lucide-react';
import { getButton, getAddresses, addAddress } from '../api/api';
import { useLocation, useNavigate } from 'react-router-dom';

const CheckoutPage = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const [addresses, setAddresses] = useState([]);
    const [selectedAddress, setSelectedAddress] = useState(null);
    const [showNewAddressForm, setShowNewAddressForm] = useState(false);
    const [formData, setFormData] = useState({
        firstName: '',
        lastName: '',
        phone: '',
        province: '',
        district: '',
        municipality: '',
        ward: '',
        streetAddress: '', 
    });
    const [paymentData, setPaymentData] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        // Check if we have a cart ID in the navigation state
        if (!location.state?.cartId) {
            setError('Invalid checkout session. Please return to cart and try again.');
            return;
        }

        // Load saved addresses
        loadAddresses();
    }, [location]);

    const loadAddresses = async () => {
        try {
            const data = await getAddresses();
            setAddresses(data);
            
            // Set default address as selected if available
            const defaultAddress = data.find(addr => addr.is_default);
            if (defaultAddress) {
                setSelectedAddress(defaultAddress);
            } else if (data.length > 0) {
                setSelectedAddress(data[0]);
            } else {
                setShowNewAddressForm(true);
            }
        } catch (err) {
            setError('Failed to load addresses. Please try again.');
            console.error('Error loading addresses:', err);
        }
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleAddressSelect = (address) => {
        setSelectedAddress(address);
        setShowNewAddressForm(false);
    };

    const handleAddNewAddress = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);

        try {
            // Convert form data to snake_case format
            const addressData = {
                first_name: formData.firstName,
                last_name: formData.lastName,
                phone: formData.phone,
                province: formData.province,
                district: formData.district,
                municipality: formData.municipality,
                ward: formData.ward,
                street_address: formData.streetAddress,
            };

            const newAddress = await addAddress(addressData);
            setAddresses(prev => [...prev, newAddress]);
            setSelectedAddress(newAddress);
            setShowNewAddressForm(false);
            setFormData({
                firstName: '',
                lastName: '',
                phone: '',
                province: '',
                district: '',
                municipality: '',
                ward: '',
                streetAddress: '',
            });
        } catch (err) {
            setError('Failed to add new address. Please try again.');
            console.error('Error adding address:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);
        
        try {
            if (!selectedAddress) {
                throw new Error('Please select or add a delivery address');
            }

            const cartId = location.state?.cartId;
            
            if (!cartId) {
                throw new Error('Invalid checkout session. Please return to cart and try again.');
            }

            const response = await getButton(cartId);
            
            if (response.success && response.data.payment_url) {
                setPaymentData(response.data);
                // Redirect to payment URL
                window.location.href = response.data.payment_url;
            } else {
                throw new Error('Invalid payment response');
            }
        } catch (err) {
            setError(err.message || 'Failed to process payment');
            console.error('Payment error:', err);
        } finally {
            setLoading(false);
        }
    };

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
                <div className="max-w-3xl mx-auto bg-gray-800 bg-opacity-70 backdrop-blur-sm rounded-xl shadow-2xl overflow-hidden">
                    <div className="p-6 sm:p-10">
                        <a
                            href="/cart"
                            className="flex items-center text-gray-400 hover:text-white mb-6"
                        >
                            <ArrowLeft className="mr-2" size={20} />
                            Back to Cart
                        </a>

                        <h1 className="text-3xl font-bold mb-8">Complete Your Purchase</h1>

                        {error && (
                            <div className="mb-6 p-4 bg-red-500 bg-opacity-20 border border-red-500 rounded-lg text-red-200">
                                {error}
                            </div>
                        )}

                        {/* Address Selection */}
                        <div className="mb-8">
                            <h2 className="text-xl font-semibold mb-4">Delivery Address</h2>
                            
                            {/* Saved Addresses */}
                            {!showNewAddressForm && addresses.length > 0 && (
                                <div className="space-y-4 mb-4">
                                    {addresses.map((address) => (
                                        <div
                                            key={address.id}
                                            onClick={() => handleAddressSelect(address)}
                                            className={`p-4 rounded-lg border cursor-pointer transition-colors ${
                                                selectedAddress?.id === address.id
                                                    ? 'border-indigo-500 bg-indigo-500 bg-opacity-10'
                                                    : 'border-gray-600 hover:border-indigo-400'
                                            }`}
                                        >
                                            <div className="flex items-start justify-between">
                                                <div>
                                                    <h3 className="font-medium">{address.full_name}</h3>
                                                    <p className="text-gray-400 mt-1">{address.phone}</p>
                                                    <p className="text-gray-400 mt-2">{address.complete_address}</p>
                                                </div>
                                                {address.is_default && (
                                                    <span className="text-xs bg-indigo-500 text-white px-2 py-1 rounded">
                                                        Default
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}

                            {/* Add New Address Button */}
                            {!showNewAddressForm && (
                                <button
                                    onClick={() => setShowNewAddressForm(true)}
                                    className="flex items-center text-indigo-400 hover:text-indigo-300"
                                >
                                    <Plus className="mr-2" size={20} />
                                    Add New Address
                                </button>
                            )}

                            {/* New Address Form */}
                            {showNewAddressForm && (
                                <form onSubmit={handleAddNewAddress} className="space-y-6 mt-4">
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label className="block text-gray-300 mb-2">First Name</label>
                                            <input
                                                type="text"
                                                name="firstName"
                                                value={formData.firstName}
                                                onChange={handleInputChange}
                                                className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-gray-300 mb-2">Last Name</label>
                                            <input
                                                type="text"
                                                name="lastName"
                                                value={formData.lastName}
                                                onChange={handleInputChange}
                                                className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-gray-300 mb-2">Phone Number</label>
                                        <input
                                            type="tel"
                                            name="phone"
                                            value={formData.phone}
                                            onChange={handleInputChange}
                                            placeholder="+977 98XXXXXXXX"
                                            className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                            required
                                        />
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label className="block text-gray-300 mb-2">Province</label>
                                            <select
                                                name="province"
                                                value={formData.province}
                                                onChange={handleInputChange}
                                                className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                                required
                                            >
                                                <option value="">Select Province</option>
                                                <option value="1">Province 1</option>
                                                <option value="2">Province 2</option>
                                                <option value="3">Province 3</option>
                                                <option value="4">Province 4</option>
                                                <option value="5">Province 5</option>
                                                <option value="6">Province 6</option>
                                                <option value="7">Province 7</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label className="block text-gray-300 mb-2">District</label>
                                            <input
                                                type="text"
                                                name="district"
                                                value={formData.district}
                                                onChange={handleInputChange}
                                                className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label className="block text-gray-300 mb-2">Municipality</label>
                                            <input
                                                type="text"
                                                name="municipality"
                                                value={formData.municipality}
                                                onChange={handleInputChange}
                                                className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-gray-300 mb-2">Ward Number</label>
                                            <input
                                                type="number"
                                                name="ward"
                                                value={formData.ward}
                                                onChange={handleInputChange}
                                                min="1"
                                                max="32"
                                                className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-gray-300 mb-2">Street Address</label>
                                        <input
                                            type="text"
                                            name="streetAddress"
                                            value={formData.streetAddress}
                                            onChange={handleInputChange}
                                            className="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                                            required
                                        />
                                    </div>

                                    <div className="flex space-x-4">
                                        <button
                                            type="submit"
                                            disabled={loading}
                                            className={`flex-1 flex items-center justify-center ${
                                                loading
                                                    ? 'bg-indigo-400 cursor-not-allowed'
                                                    : 'bg-indigo-600 hover:bg-indigo-700'
                                            } text-white py-3 px-6 rounded-lg font-medium transition duration-200`}
                                        >
                                            <MapPin className="mr-2" />
                                            {loading ? 'Saving...' : 'Save Address'}
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => setShowNewAddressForm(false)}
                                            className="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg font-medium transition duration-200"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            )}
                        </div>

                        {/* Payment Button */}
                        <form onSubmit={handleSubmit}>
                            <button
                                type="submit"
                                disabled={loading || !selectedAddress}
                                className={`w-full flex items-center justify-center ${
                                    loading || !selectedAddress
                                        ? 'bg-indigo-400 cursor-not-allowed'
                                        : 'bg-indigo-600 hover:bg-indigo-700'
                                } text-white py-3 px-6 rounded-lg font-medium transition duration-200`}
                            >
                                <CreditCard className="mr-2" />
                                {loading ? 'Processing...' : 'Pay Now'}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CheckoutPage;
