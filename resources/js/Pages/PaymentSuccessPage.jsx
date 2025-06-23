import React, { useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { CheckCircle } from 'lucide-react';

const PaymentSuccessPage = () => {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    useEffect(() => {
        // Redirect to profile after 5 seconds
        const timer = setTimeout(() => {
            navigate('/profile');
        }, 5000);

        return () => clearTimeout(timer);
    }, [navigate]);

    const message = searchParams.get('message');
    const purchasesCount = searchParams.get('purchases_count');
    const totalAmount = searchParams.get('total_amount');

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-900 to-indigo-950 text-gray-100 flex items-center justify-center">
            <div className="max-w-md w-full mx-auto p-8 bg-gray-800 bg-opacity-70 backdrop-blur-sm rounded-xl shadow-2xl">
                <div className="text-center">
                    <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500 bg-opacity-20 mb-6">
                        <CheckCircle className="w-8 h-8 text-green-400" />
                    </div>
                    
                    <h1 className="text-2xl font-bold mb-4">Payment Successful!</h1>
                    
                    <p className="text-gray-300 mb-6">{message}</p>
                    
                    <div className="bg-gray-700 bg-opacity-50 rounded-lg p-4 mb-6">
                        <div className="flex justify-between mb-2">
                            <span className="text-gray-400">Items Purchased:</span>
                            <span className="text-white font-medium">{purchasesCount}</span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-gray-400">Total Amount:</span>
                            <span className="text-white font-medium">Rs{totalAmount}</span>
                        </div>
                    </div>
                    
                    <p className="text-gray-400 text-sm">
                        Redirecting to your profile in 5 seconds...
                    </p>
                </div>
            </div>
        </div>
    );
};

export default PaymentSuccessPage; 