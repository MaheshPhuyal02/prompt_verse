import React, { useState } from 'react';
import { ShoppingCart, Trash2, CreditCard, AlertCircle, CheckCircle } from 'lucide-react';

const CartPage = () => {
    // Sample cart data
    const [cart, setCart] = useState([
        { id: 1, title: "GPT-4 Creative Writing Prompt", price: 4.99 },
        { id: 2, title: "Code Optimization Assistant", price: 9.99 },
        { id: 3, title: "Data Analysis Template", price: 7.49 }
    ]);

    const [notification, setNotification] = useState(null);

    // Calculate total
    const total = cart.reduce((sum, item) => sum + item.price, 0);

    const removeFromCart = (id) => {
        setCart(cart.filter(item => item.id !== id));
    };

    const clearCart = () => {
        setCart([]);
    };

    const handleCheckout = async () => {
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));

            clearCart();
            setNotification({
                type: 'success',
                message: 'Purchase successful! Your prompts are now available in your library.'
            });

            // Clear notification after 3 seconds
            setTimeout(() => setNotification(null), 3000);

        } catch (error) {
            setNotification({
                type: 'error',
                message: 'Failed to complete purchase. Please try again.'
            });

            // Clear notification after 3 seconds
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
                            <h1 className="text-3xl font-bold flex items-center">
                                <ShoppingCart className="mr-3" />
                                Shopping Cart
                            </h1>
                            {cart.length > 0 && (
                                <button
                                    onClick={clearCart}
                                    className="flex items-center text-gray-400 hover:text-red-400 transition duration-200"
                                >
                                    <Trash2 size={18} className="mr-1" />
                                    <span className="text-sm">Clear All</span>
                                </button>
                            )}
                        </div>

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

                        {cart.length === 0 ? (
                            <div className="text-center py-16">
                                <ShoppingCart size={48} className="mx-auto text-gray-500 mb-4" />
                                <p className="text-xl text-gray-400">Your cart is empty</p>
                                <p className="text-gray-500 mt-2">Browse our prompt marketplace to find AI assistants that can help with your tasks.</p>
                            </div>
                        ) : (
                            <>
                                <div className="space-y-4 divide-y divide-gray-700">
                                    {cart.map((item) => (
                                        <div key={item.id} className="flex justify-between items-center py-4">
                                            <div>
                                                <h3 className="text-xl font-medium">{item.title}</h3>
                                                <p className="text-indigo-300 font-semibold">${item.price.toFixed(2)}</p>
                                            </div>
                                            <button
                                                onClick={() => removeFromCart(item.id)}
                                                className="text-gray-400 hover:text-red-400 transition duration-200"
                                            >
                                                <Trash2 size={20} />
                                            </button>
                                        </div>
                                    ))}
                                </div>

                                <div className="mt-8 border-t border-gray-700 pt-6">
                                    <div className="flex justify-between items-center mb-6">
                                        <span className="text-xl font-medium">Total</span>
                                        <span className="text-2xl font-bold text-indigo-300">${total.toFixed(2)}</span>
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
                            </>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CartPage;
