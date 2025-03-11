import React from "react";
import { ToastContainer } from "react-toastify";
import { BrowserRouter } from "react-router-dom";
import "react-toastify/dist/ReactToastify.css";
import "bootstrap/dist/css/bootstrap.min.css";
import IndexRoutes from "../routes/IndexRoutes";
import "./App.css";

export default function App() {
  return (
    <BrowserRouter>
      <div id="app-content">
        <IndexRoutes />
        <ToastContainer autoClose={3000} position="bottom-right" />
      </div>
    </BrowserRouter>
  );
}