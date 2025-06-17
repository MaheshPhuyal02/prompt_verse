import './bootstrap';
import "../css/app.css";

import React, {useState} from 'react';
import ReactDOM from "react-dom/client";

import { BrowserRouter as Router, Route, Routes, Link } from 'react-router-dom';
import MainApp from "./MainApp";

ReactDOM.createRoot(document.getElementById("app")).render(
    <React.StrictMode>
        <MainApp />
    </React.StrictMode>
);




