import React from "react";
import { ToastContainer } from "react-toastify";
import { BrowserRouter } from "react-router-dom";
import "react-toastify/dist/ReactToastify.css";
import "bootstrap/dist/css/bootstrap.min.css";
import Sidebar from "../common/generic/sidebar/Sidebar";
import "./App.css";

export default function App() {
  return (
    <BrowserRouter>
      <div className="app-wrapper">
        <Sidebar />
        <div id="app-content">
          {/* Tu możesz umieścić resztę treści, np. routes */}
          <ToastContainer autoClose={3000} position="bottom-right" />
        </div>
      </div>
    </BrowserRouter>
  );
}