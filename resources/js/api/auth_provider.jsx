import React, { createContext, useState, useContext, useEffect } from "react";

// Create AuthContext
const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    // Check if user is already logged in (from local storage)
    useEffect(() => {
        const storedUser = localStorage.getItem("user");
        if (storedUser) {
            setUser(JSON.parse(storedUser));
        }
        setLoading(false);
    }, []);

    // Login function
    const login = async (email, password) => {
        try {
            // API call to validate credentials and get authentication key
            const response = await fetch("https://api.example.com/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email, password }),
            });

            if (!response.ok) {
                throw new Error("Login failed");
            }

            const data = await response.json();

            // Store user info in state and local storage
            const userData = {
                email,
                name: data.name,
                apiKey: data.apiKey,
            };

            setUser(userData);
            localStorage.setItem("user", JSON.stringify(userData));
            return true;
        } catch (error) {
            console.error("Login error:", error);
            return false;
        }
    };

    // Signup function
    const signup = async (name, email, password) => {
        try {
            // API call to create a new user
            const response = await fetch("https://api.example.com/signup", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ name, email, password }),
            });

            if (!response.ok) {
                throw new Error("Signup failed");
            }

            const data = await response.json();

            // Store user info in state and local storage
            const userData = {
                email,
                name,
                apiKey: data.apiKey,
            };

            setUser(userData);
            localStorage.setItem("user", JSON.stringify(userData));
            return true;
        } catch (error) {
            console.error("Signup error:", error);
            return false;
        }
    };

    // Logout function
    const logout = () => {
        setUser(null);
        localStorage.removeItem("user");
    };

    // Check if user is logged in
    const isLoggedIn = () => {
        return user !== null;
    };

    // Get current user
    const getCurrentUser = () => {
        return user;
    };

    // Create the auth context value object
    const value = {
        user,
        login,
        signup,
        logout,
        isLoggedIn,
        getCurrentUser,
        loading
    };

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

// Custom hook to use the auth context
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (context === null) {
        throw new Error("useAuth must be used within an AuthProvider");
    }
    return context;
};

// Protected Route component
export const ProtectedRoute = ({ children }) => {
    const { isLoggedIn, loading } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        if (!loading && !isLoggedIn()) {
            navigate("/login");
        }
    }, [isLoggedIn, loading, navigate]);

    if (loading) {
        return <div>Loading...</div>;
    }

    return isLoggedIn() ? children : null;
};

export default AuthProvider;
