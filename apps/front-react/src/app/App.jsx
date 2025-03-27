import React from "react";
import { ToastContainer } from "react-toastify";
import { BrowserRouter } from "react-router-dom";
import "react-toastify/dist/ReactToastify.css";
import "bootstrap/dist/css/bootstrap.min.css";
import IndexRoutes from "../routes/IndexRoutes";
import "./App.css";
import { AuthProvider } from "../context/AuthContext";

export default function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <div id="app-content">
          <IndexRoutes />
          <ToastContainer autoClose={3000} position="bottom-center" toastClassName="toast-large"/>
        </div>
      </AuthProvider>
    </BrowserRouter>
  );
}