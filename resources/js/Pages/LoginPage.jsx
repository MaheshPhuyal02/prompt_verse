import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { LogIn, User, Lock, Mail, Eye, EyeOff, AlertCircle, ArrowRight, Phone } from 'lucide-react';
import {useAuth} from "../api/auth_provider.jsx";


const LoginPage = () => {
    const [isLogin, setIsLogin] = useState(true);
    const [showPassword, setShowPassword] = useState(false);
    const [formData, setFormData] = useState({
        email: '',
        password: '',
        name: '',
        phone: '',
        confirmPassword: ''
    });
    const [errors, setErrors] = useState({});
    const [isLoading, setIsLoading] = useState(false);

    const navigate = useNavigate();
    const { login, signup, isLoggedIn } = useAuth();

    // Redirect if already logged in
    useEffect(() => {
        if (isLoggedIn()) {
            navigate('/dashboard');
        }
    }, [isLoggedIn, navigate]);

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

            if (formData.phone && !/^\+?[\d\s-]+$/.test(formData.phone)) {
                newErrors.phone = 'Invalid phone number format';
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
            let success;

            if (isLogin) {
                // Handle login
                success = await login(formData.email, formData.password);
            } else {
                // Handle signup
                success = await signup(formData.name, formData.email, formData.password, formData.phone);
            }

            if (success) {
                window.location.href = '/';

            } else {
                setErrors({
                    general: isLogin ? 'Invalid email or password.' : 'Failed to create account.'
                });
            }
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

                            {/* Phone field (signup only) */}
                            {!isLogin && (
                                <div className="mb-4">
                                    <label htmlFor="phone" className="block text-gray-300 text-sm font-medium mb-2">
                                        Phone Number (Optional)
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Phone size={18} className="text-gray-500" />
                                        </div>
                                        <input
                                            id="phone"
                                            name="phone"
                                            type="tel"
                                            value={formData.phone}
                                            onChange={handleChange}
                                            className={`bg-gray-900 text-white w-full pl-10 pr-4 py-2 rounded-lg focus:ring-2 ${
                                                errors.phone ? 'border border-red-500 focus:ring-red-500' : 'border border-gray-700 focus:ring-indigo-500'
                                            }`}
                                            placeholder="+1 (123) 456-7890"
                                        />
                                    </div>
                                    {errors.phone && (
                                        <p className="mt-1 text-sm text-red-400">{errors.phone}</p>
                                    )}
                                </div>
                            )}

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

                    </div>
                </div>

            </div>
        </div>
    );
};

export default LoginPage;
