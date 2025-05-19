import './bootstrap';
import "../css/app.css";

import React, {useState} from 'react';
import ReactDOM from "react-dom/client";

import { BrowserRouter as Router, Route, Routes, Link } from 'react-router-dom';
import HomePage from './pages/Home.jsx';
import CartPage from './pages/CartPage';
import ProfilePage from './pages/ProfilePage';
import AdminPage from './pages/AdminPage';
import MainApp from "./MainApp";

ReactDOM.createRoot(document.getElementById("app")).render(
    <React.StrictMode>
        <MainApp />
    </React.StrictMode>
);




