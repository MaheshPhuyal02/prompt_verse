import React, {useEffect, useState} from 'react';
import { ShoppingCart, Trash2, CreditCard, AlertCircle, CheckCircle } from 'lucide-react';
import {getAllCarts, deleteFromCart} from "../api/api.js";

const CartPage = () => {
    const [cartData, setCartData] = useState({
        items: [],
        summary: null
    });

    const [notification, setNotification] = useState(null);
    const [isLoading, setIsLoading] = useState(true);

    const fetchCartData = async () => {
        try {
            setIsLoading(true);
            const data = await getAllCarts();
            setCartData(data);
        } catch (error) {
            console.error('Error fetching cart data:', error);
            setNotification({
                type: 'error',
                message: 'Failed to load cart. Please try again later.'
            });
        } finally {
            setIsLoading(false);
        }
    };

    const removeFromCart = async (id) => {
        try {
            await deleteFromCart(id);
            // Refresh cart data after deletion
            await fetchCartData();
            setNotification({
                type: 'success',
                message: 'Item removed from cart successfully.'
            });
            setTimeout(() => setNotification(null), 3000);
        } catch (error) {
            console.error('Error removing item from cart:', error);
            setNotification({
                type: 'error',
                message: 'Failed to remove item from cart. Please try again.'
            });
            setTimeout(() => setNotification(null), 3000);
        }
    };

    useEffect(() => {
        fetchCartData();
    }, []);

    const handleCheckout = async () => {
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // Refresh cart data after checkout
            await fetchCartData();
            
            setNotification({
                type: 'success',
                message: 'Purchase successful! Your prompts are now available in your library.'
            });

            setTimeout(() => setNotification(null), 3000);
        } catch (error) {
            console.error('Error during checkout:', error);
            setNotification({
                type: 'error',
                message: 'Failed to complete purchase. Please try again.'
            });

            setTimeout(() => setNotification(null), 3000);
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
                        <div className="flex items-center justify-between mb-8">
                            <div>
                                <h1 className="text-3xl font-bold flex items-center">
                                    <ShoppingCart className="mr-3" />
                                    Shopping Cart
                                </h1>
                                <p className="text-gray-400 mt-2">
                                    {cartData.items?.length || 0} items in cart
                                </p>
                            </div>
                        </div>

                        {/* Loading state */}
                        {isLoading ? (
                            <div className="text-center py-16">
                                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto"></div>
                                <p className="text-gray-400 mt-4">Loading cart...</p>
                            </div>
                        ) : (
                            <>
                                {/* Notification */}
                                {notification && (
                                    <div className={`mb-6 px-4 py-3 rounded-lg flex items-center ${
                                        notification.type === 'success' ? 'bg-green-900 bg-opacity-40 text-green-300' : 'bg-red-900 bg-opacity-40 text-red-300'
                                    }`}>
                                        {notification.type === 'success' ?
                                            <CheckCircle size={20} className="mr-2" /> :
                                            <AlertCircle size={20} className="mr-2" />
                                        }
                                        <p>{notification.message}</p>
                                    </div>
                                )}

                                {(!cartData.items || cartData.items.length === 0) ? (
                                    <div className="text-center py-16">
                                        <ShoppingCart size={48} className="mx-auto text-gray-500 mb-4" />
                                        <p className="text-xl text-gray-400">Your cart is empty</p>
                                        <p className="text-gray-500 mt-2">Browse our prompt marketplace to find AI assistants that can help with your tasks.</p>
                                    </div>
                                ) : (
                                    <>
                                        <div className="space-y-4 divide-y divide-gray-700">
                                            {cartData.items.map((item) => (
                                                <div key={item.id} className="flex justify-between items-start py-4">
                                                    <div className="flex-grow">
                                                        <h3 className="text-xl font-medium">{item.prompt.title}</h3>
                                                        <p className="text-gray-400 mt-1">{item.prompt.description}</p>
                                                        <div className="mt-2 flex items-center space-x-4">
                                                            <span className="text-indigo-300 font-semibold">
                                                                ${item.price_at_time} × {item.quantity}
                                                            </span>
                                                            <span className="text-gray-400">|</span>
                                                            <span className="text-indigo-300 font-bold">
                                                                Total: ${item.total_price}
                                                            </span>
                                                        </div>
                                                        <div className="mt-1 text-sm text-gray-400">
                                                            Added on: {item.added_at}
                                                        </div>
                                                    </div>
                                                    <button
                                                        onClick={() => removeFromCart(item.id)}
                                                        className="text-gray-400 hover:text-red-400 transition duration-200 ml-4"
                                                    >
                                                        <Trash2 size={20} />
                                                    </button>
                                                </div>
                                            ))}
                                        </div>

                                        {cartData.summary && (
                                            <div className="mt-8 border-t border-gray-700 pt-6">
                                                <div className="space-y-2 mb-6">
                                                    <div className="flex justify-between items-center text-gray-400">
                                                        <span>Items Count</span>
                                                        <span>{cartData.summary.items_count}</span>
                                                    </div>
                                                    <div className="flex justify-between items-center text-gray-400">
                                                        <span>Total Items</span>
                                                        <span>{cartData.summary.total_items}</span>
                                                    </div>
                                                    <div className="flex justify-between items-center text-2xl font-bold text-indigo-300">
                                                        <span>Total Amount</span>
                                                        <span>${cartData.summary.total_amount}</span>
                                                    </div>
                                                </div>

                                                <button
                                                    onClick={handleCheckout}
                                                    className="w-full flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-6 rounded-lg font-medium transition duration-200"
                                                >
                                                    <CreditCard className="mr-2" />
                                                    Complete Purchase
                                                </button>

                                                <p className="text-xs text-gray-400 text-center mt-4">
                                                    By completing this purchase, you agree to our Terms of Service and Privacy Policy.
                                                </p>
                                            </div>
                                        )}
                                    </>
                                )}
                            </>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CartPage;
