import React, {useState} from "react";
import { BrowserRouter as Router, Link, Route, Routes, Navigate } from "react-router-dom";
import { AuthProvider, useAuth, ProtectedRoute } from "./api/auth_provider.jsx";

import CartPage from "./Pages/CartPage.jsx";
import LoginPage from "./Pages/LoginPage.jsx";
import CheckoutPage from "./Pages/CheckoutPage.jsx";
import Navigation from "./compontents/Navigation.jsx";
import {addToCart, isAuthenticated} from "./api/api.js";
import HomePage from "./Pages/Home.jsx";
import ProfilePage from "./Pages/ProfilePage.jsx";
import PaymentSuccessPage from "./Pages/PaymentSuccessPage.jsx";
import PaymentFailedPage from "./Pages/PaymentFailedPage.jsx";


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
                            <Route
                                path="/cart"
                                element={
                                    <CartPage
                                    />
                                }
                            />
                            <Route
                                path="/checkout"
                                element={
                                    <ProtectedRoute>
                                        <CheckoutPage />
                                    </ProtectedRoute>
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
                                path="/payment/success"
                                element={
                                    <ProtectedRoute>
                                        <PaymentSuccessPage />
                                    </ProtectedRoute>
                                }
                            />
                            <Route
                                path="/payment/failed"
                                element={
                                    <ProtectedRoute>
                                        <PaymentFailedPage />
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
