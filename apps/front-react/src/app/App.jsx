import React from "react";
import { ToastContainer } from "react-toastify";
import { BrowserRouter } from "react-router-dom";
import "react-toastify/dist/ReactToastify.css";
import "bootstrap/dist/css/bootstrap.min.css";
import IndexRoutes from "../routes/IndexRoutes";
import "./App.css";
import { AuthProvider } from "../context/AuthContext";
import { TripProvider } from "../context/TripContext";
import { ScheduleProvider } from "../context/ScheduleContext";
export default function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <TripProvider>
          <ScheduleProvider>
            <div id="app-content">
              <IndexRoutes />
              <ToastContainer autoClose={3000} position="top-right" toastClassName="toast-large"/>
            </div>
          </ScheduleProvider>
        </TripProvider>
      </AuthProvider>
    </BrowserRouter>
  );
}