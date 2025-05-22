import React, {useState} from "react";
import {Brain, Search, ShoppingCart, User, RefreshCw} from "lucide-react";
import {Link} from "react-router-dom";


function Navigation() {
    const [searchTerm, setSearchTerm] = useState('');

    // User authentication functions (simplified)
    const isLoggedIn = () => {
        // Example function to check login status
        return localStorage.getItem('user') !== null;
    };

    const logout = () => {
        // Example logout function
        localStorage.removeItem('user');
        window.location.href = '/';
    };


    return (
        <header className="relative z-10 bg-gray-900 bg-opacity-80 backdrop-blur-sm border-b border-indigo-800">
            <div className="container mx-auto px-4 py-4 flex justify-between items-center">
                <div className="flex items-center space-x-2">
                    <Brain className="text-indigo-400" size={28} />
                    <h1 className="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">
                        Promptverse
                    </h1>
                </div>

                <div className="flex items-center space-x-4">
                    <div className="relative">
                        <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" size={18} />
                        <input
                            type="text"
                            placeholder="Search prompts..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="bg-gray-800 rounded-full pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-gray-700 w-64"
                        />
                    </div>

                    {/*<div className="hidden md:flex space-x-4 text-gray-300 mr-4">*/}
                    {/*    <Link to="/" className="hover:text-white">Home</Link>*/}
                    {/*    <Link to="/browse" className="hover:text-white">Browse</Link>*/}
                    {/*    <Link to="/categories" className="hover:text-white">Categories</Link>*/}
                    {/*</div>*/}



                    <Link to="/cart" className="relative bg-indigo-600 hover:bg-indigo-700 rounded-full p-2 transition-colors">
                        <ShoppingCart size={20} />
                        <span className="absolute -top-1 -right-1 w-5 h-5 bg-purple-500 rounded-full text-xs flex items-center justify-center">3</span>
                    </Link>

                    {isLoggedIn() ? (
                        <>
                            <Link to="/profile" className="flex items-center space-x-1 bg-indigo-700 hover:bg-indigo-800 px-3 py-2 rounded-lg text-white transition-colors">
                                <User size={18} />
                                <span>Profile</span>
                            </Link>
                            <button
                                onClick={logout}
                                className="bg-red-500 hover:bg-red-600 px-3 py-2 rounded-lg text-white"
                            >
                                Logout
                            </button>
                        </>
                    ) : (
                        <Link to="/login" className="bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded-lg text-white transition-colors">
                            Login
                        </Link>
                    )}
                </div>
            </div>
        </header>
    );
}

export default Navigation;
