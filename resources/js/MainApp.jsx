import React, {useState} from "react";
import { BrowserRouter as Router, Link, Route, Routes, Navigate } from "react-router-dom";
import { AuthProvider, useAuth, ProtectedRoute } from "./api/auth_provider.jsx";
import HomePage from "./Pages/Home.jsx";
import CartPage from "./Pages/CartPage.jsx";
import ProfilePage from "./Pages/ProfilePage.jsx";
import AdminPage from "./Pages/AdminPage.jsx";
import LoginPage from "./Pages/LoginPage.jsx";
import Navigation from "./compontents/Navigation.jsx";
import {addToCart, isAuthenticated} from "./api/api.js";


// Navigation component with authentication status

const MainApp = () => {
    // index.php
    // login.php

    return (
        <AuthProvider>
            <Router>
                <div className="min-h-screen bg-gray-50">
                    <Navigation />

                    <div className="container mx-auto">
                        <Routes>
                            <Route path="/login" element={<LoginPage />} />
                            <Route path="/" element={<HomePage />} />
                            <Route path="/" element={<AdminPage />} />
                            <Route
                                path="/cart"
                                element={
                                    <CartPage
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
