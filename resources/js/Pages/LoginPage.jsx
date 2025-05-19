import React, { useState } from 'react';
import { LogIn, User, Lock, Mail, Eye, EyeOff, AlertCircle, ArrowRight } from 'lucide-react';

const LoginPage = () => {
    const [isLogin, setIsLogin] = useState(true);
    const [showPassword, setShowPassword] = useState(false);
    const [formData, setFormData] = useState({
        email: '',
        password: '',
        name: '',
        confirmPassword: ''
    });
    const [errors, setErrors] = useState({});
    const [isLoading, setIsLoading] = useState(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value
        });

        // Clear error when typing
        if (errors[name]) {
            setErrors({
                ...errors,
                [name]: null
            });
        }
    };

    const validateForm = () => {
        const newErrors = {};

        if (!formData.email) {
            newErrors.email = 'Email is required';
        } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
            newErrors.email = 'Email address is invalid';
        }

        if (!formData.password) {
            newErrors.password = 'Password is required';
        } else if (formData.password.length < 6) {
            newErrors.password = 'Password must be at least 6 characters';
        }

        if (!isLogin) {
            if (!formData.name) {
                newErrors.name = 'Name is required';
            }

            if (formData.password !== formData.confirmPassword) {
                newErrors.confirmPassword = 'Passwords do not match';
            }
        }

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        setIsLoading(true);

        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));

            // This would be where you'd handle the login/signup logic
            console.log('Form submitted:', formData);

            // Redirect to dashboard or other page on success
        } catch (error) {
            setErrors({
                general: 'An error occurred. Please try again.'
            });
        } finally {
            setIsLoading(false);
        }
    };

    const toggleMode = () => {
        setIsLogin(!isLogin);
        setErrors({});
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-900 to-indigo-950 text-gray-100 flex items-center justify-center">
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
            <div className="w-full max-w-md px-4 py-8">
                <div className="bg-gray-800 bg-opacity-70 backdrop-blur-sm rounded-xl shadow-2xl overflow-hidden">
                    {/* Header */}
                    <div className="bg-indigo-900 bg-opacity-80 p-6 flex items-center justify-between">
                        <h1 className="text-2xl font-bold flex items-center">
                            {isLogin ? (
                                <>
                                    <LogIn className="mr-3" />
                                    Login
                                </>
                            ) : (
                                <>
                                    <User className="mr-3" />
                                    Sign Up
                                </>
                            )}
                        </h1>
                        <div className="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center">
                            <User size={24} />
                        </div>
                    </div>

                    {/* Form */}
                    <div className="p-6">
                        {errors.general && (
                            <div className="mb-6 px-4 py-3 rounded-lg flex items-center bg-red-900 bg-opacity-40 text-red-300">
                                <AlertCircle size={20} className="mr-2" />
                                <p>{errors.general}</p>
                            </div>
                        )}

                        <form onSubmit={handleSubmit}>
                            {/* Name field (signup only) */}
                            {!isLogin && (
                                <div className="mb-4">
                                    <label htmlFor="name" className="block text-gray-300 text-sm font-medium mb-2">
                                        Full Name
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <User size={18} className="text-gray-500" />
                                        </div>
                                        <input
                                            id="name"
                                            name="name"
                                            type="text"
                                            value={formData.name}
                                            onChange={handleChange}
                                            className={`bg-gray-900 text-white w-full pl-10 pr-4 py-2 rounded-lg focus:ring-2 ${
                                                errors.name ? 'border border-red-500 focus:ring-red-500' : 'border border-gray-700 focus:ring-indigo-500'
                                            }`}
                                            placeholder="Your Name"
                                        />
                                    </div>
                                    {errors.name && (
                                        <p className="mt-1 text-sm text-red-400">{errors.name}</p>
                                    )}
                                </div>
                            )}

                            {/* Email field */}
                            <div className="mb-4">
                                <label htmlFor="email" className="block text-gray-300 text-sm font-medium mb-2">
                                    Email Address
                                </label>
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <Mail size={18} className="text-gray-500" />
                                    </div>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        className={`bg-gray-900 text-white w-full pl-10 pr-4 py-2 rounded-lg focus:ring-2 ${
                                            errors.email ? 'border border-red-500 focus:ring-red-500' : 'border border-gray-700 focus:ring-indigo-500'
                                        }`}
                                        placeholder="name@example.com"
                                    />
                                </div>
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-400">{errors.email}</p>
                                )}
                            </div>

                            {/* Password field */}
                            <div className="mb-4">
                                <label htmlFor="password" className="block text-gray-300 text-sm font-medium mb-2">
                                    Password
                                </label>
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <Lock size={18} className="text-gray-500" />
                                    </div>
                                    <input
                                        id="password"
                                        name="password"
                                        type={showPassword ? 'text' : 'password'}
                                        value={formData.password}
                                        onChange={handleChange}
                                        className={`bg-gray-900 text-white w-full pl-10 pr-10 py-2 rounded-lg focus:ring-2 ${
                                            errors.password ? 'border border-red-500 focus:ring-red-500' : 'border border-gray-700 focus:ring-indigo-500'
                                        }`}
                                        placeholder="••••••••"
                                    />
                                    <button
                                        type="button"
                                        className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-300"
                                        onClick={() => setShowPassword(!showPassword)}
                                    >
                                        {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                                    </button>
                                </div>
                                {errors.password && (
                                    <p className="mt-1 text-sm text-red-400">{errors.password}</p>
                                )}
                            </div>

                            {/* Confirm Password field (signup only) */}
                            {!isLogin && (
                                <div className="mb-4">
                                    <label htmlFor="confirmPassword" className="block text-gray-300 text-sm font-medium mb-2">
                                        Confirm Password
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Lock size={18} className="text-gray-500" />
                                        </div>
                                        <input
                                            id="confirmPassword"
                                            name="confirmPassword"
                                            type={showPassword ? 'text' : 'password'}
                                            value={formData.confirmPassword}
                                            onChange={handleChange}
                                            className={`bg-gray-900 text-white w-full pl-10 pr-10 py-2 rounded-lg focus:ring-2 ${
                                                errors.confirmPassword ? 'border border-red-500 focus:ring-red-500' : 'border border-gray-700 focus:ring-indigo-500'
                                            }`}
                                            placeholder="••••••••"
                                        />
                                    </div>
                                    {errors.confirmPassword && (
                                        <p className="mt-1 text-sm text-red-400">{errors.confirmPassword}</p>
                                    )}
                                </div>
                            )}

                            {/* Submit Button */}
                            <div className="mt-6">
                                <button
                                    type="submit"
                                    disabled={isLoading}
                                    className={`w-full flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 ${
                                        isLoading ? 'opacity-70 cursor-not-allowed' : ''
                                    }`}
                                >
                                    {isLoading ? (
                                        <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    ) : isLogin ? (
                                        <>
                                            Sign In <ArrowRight size={18} className="ml-2" />
                                        </>
                                    ) : (
                                        <>
                                            Create Account <ArrowRight size={18} className="ml-2" />
                                        </>
                                    )}
                                </button>
                            </div>

                            {/* Forgot Password (login only) */}
                            {isLogin && (
                                <div className="mt-4 text-center">
                                    <a href="#forgot-password" className="text-indigo-300 hover:text-indigo-200 text-sm">
                                        Forgot your password?
                                    </a>
                                </div>
                            )}
                        </form>

                        {/* Toggle between login and signup */}
                        <div className="mt-6 pt-4 border-t border-gray-700 text-center">
                            <p className="text-gray-400">
                                {isLogin ? "Don't have an account? " : "Already have an account? "}
                                <button
                                    onClick={toggleMode}
                                    className="text-indigo-300 hover:text-indigo-200 font-medium"
                                >
                                    {isLogin ? "Sign Up" : "Sign In"}
                                </button>
                            </p>
                        </div>

                        {/* OAuth options */}
                        <div className="mt-6">
                            <div className="relative">
                                <div className="absolute inset-0 flex items-center">
                                    <div className="w-full border-t border-gray-700"></div>
                                </div>
                                <div className="relative flex justify-center text-sm">
                                    <span className="px-2 bg-gray-800 text-gray-400">Or continue with</span>
                                </div>
                            </div>

                            <div className="mt-6 grid grid-cols-2 gap-3">
                                <button className="w-full bg-gray-900 hover:bg-gray-800 text-white py-2 px-4 rounded-lg border border-gray-700 flex items-center justify-center">
                                    <svg className="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                    </svg>
                                    Google
                                </button>
                                <button className="w-full bg-gray-900 hover:bg-gray-800 text-white py-2 px-4 rounded-lg border border-gray-700 flex items-center justify-center">
                                    <svg className="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                                    </svg>
                                    Facebook
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Footer */}
                <div className="mt-6 text-center">
                    <p className="text-gray-500 text-sm">
                        By signing in, you agree to our
                        <a href="#terms" className="text-indigo-400 hover:text-indigo-300 mx-1">Terms of Service</a>
                        and
                        <a href="#privacy" className="text-indigo-400 hover:text-indigo-300 mx-1">Privacy Policy</a>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default LoginPage;
