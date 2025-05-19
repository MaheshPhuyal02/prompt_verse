import React from "react";
import { BrowserRouter as Router, Link, Route, Routes, Navigate } from "react-router-dom";
import { AuthProvider, useAuth, ProtectedRoute } from "./api/auth_provider.jsx";
import HomePage from "./Pages/Home.jsx";
import CartPage from "./Pages/CartPage.jsx";
import ProfilePage from "./Pages/ProfilePage.jsx";
import AdminPage from "./Pages/AdminPage.jsx";
import LoginPage from "./Pages/LoginPage.jsx";

// Navigation component with authentication status
const Navigation = () => {
    const { isLoggedIn, logout } = useAuth();

    return (
        <nav className="bg-gray-800 text-white p-4">
            <div className="container mx-auto flex justify-between items-center">
                <Link to="/" className="text-xl font-bold">Prompt Store</Link>
                <div className="space-x-4">
                    <Link to="/" className="hover:text-gray-300">Home</Link>
                    <Link to="/cart" className="hover:text-gray-300">Cart</Link>

                    {isLoggedIn() ? (
                        <>
                            <Link to="/profile" className="hover:text-gray-300">Profile</Link>
                            <Link to="/admin" className="hover:text-gray-300">Admin</Link>
                            <button
                                onClick={logout}
                                className="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white"
                            >
                                Logout
                            </button>
                        </>
                    ) : (
                        <Link to="/login" className="hover:text-gray-300">Login</Link>
                    )}
                </div>
            </div>
        </nav>
    );
};

const MainApp = () => {
    const [cart, setCart] = React.useState([]);

    const addToCart = (prompt) => {
        setCart(prevCart => [...prevCart, prompt]);
    };

    const removeFromCart = (promptId) => {
        setCart(prevCart => prevCart.filter(item => item.id !== promptId));
    };

    const clearCart = () => {
        setCart([]);
    };

    return (
        <AuthProvider>
            <Router>
                <div className="min-h-screen bg-gray-50">
                    <Navigation />

                    <div className="container mx-auto">
                        <Routes>
                            <Route path="/login" element={<LoginPage />} />
                            <Route path="/" element={<HomePage addToCart={addToCart} />} />
                            <Route
                                path="/cart"
                                element={
                                    <CartPage
                                        cart={cart}
                                        removeFromCart={removeFromCart}
                                        clearCart={clearCart}
                                    />
                                }
                            />
                            <Route
                                path="/profile"
                                element={
                                    <ProtectedRoute>
                                        <ProfilePage />
                                    </ProtectedRoute>
                                }
                            />
                            <Route
                                path="/admin"
                                element={
                                    <ProtectedRoute>
                                        <AdminPage />
                                    </ProtectedRoute>
                                }
                            />
                            <Route path="*" element={<Navigate to="/" />} />
                        </Routes>
                    </div>
                </div>
            </Router>
        </AuthProvider>
    );
};

export default MainApp;
