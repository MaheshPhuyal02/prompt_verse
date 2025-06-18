import React, { useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { XCircle } from 'lucide-react';

const PaymentFailedPage = () => {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    // useEffect(() => {
    //     // Redirect to cart after 5 seconds
    //     const timer = setTimeout(() => {
    //         navigate('/cart');
    //     }, 5000);
    //
    //     return () => clearTimeout(timer);
    // }, [navigate]);

    const message = searchParams.get('message') || 'Payment failed. Please try again.';
    const error = searchParams.get('error') || 'Payment failed. Please try again.';

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-900 to-indigo-950 text-gray-100 flex items-center justify-center">
            <div className="max-w-md w-full mx-auto p-8 bg-gray-800 bg-opacity-70 backdrop-blur-sm rounded-xl shadow-2xl">
                <div className="text-center">
                    <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500 bg-opacity-20 mb-6">
                        <XCircle className="w-8 h-8 text-red-400" />
                    </div>

                    <h1 className="text-2xl font-bold mb-4">Payment Failed</h1>

                    <p className="text-gray-300 mb-6">{message}</p>
                    <p className="text-red-400 mb-6">{error}</p>

                    <p className="text-gray-400 text-sm">
                        Redirecting to your cart in 5 seconds...
                    </p>
                </div>
            </div>
        </div>
    );
};

export default PaymentFailedPage;
