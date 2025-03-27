import React from "react";
import { Routes, Route, Navigate } from "react-router-dom";
import { useSelector } from "react-redux";
import Home from "../common/home/Home";
import Dashboard from "../common/dashboard/Dashboard";
import Error from "../common/generic/error/Error";
import Login from "../common/generic/login/Login";
import Signup from "../common/generic/signup/Signup";
import Center from "../common/generic/center/Center";
import Profile from "../common/generic/profile/Profile";
import CreateTrip from "../common/generic/createtrip/CreateTrip";
import Schedule from "../common/generic/schedule/Schedule";
import Trips from "../common/generic/trips/Trips";
import Billing from "../common/generic/billing/Billing";
import { useAuth } from "../context/useAuth";

export default function IndexRoutes() {
  const { isAuthenticated, loading } = useAuth();
  if (loading) return <p>SimpleTravel - Ładowanie sesji...</p>;

  return (
    <div id="body-content">
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="login" element={<Login />} />
        <Route path="signup" element={<Signup />} />

        <Route
          path="dashboard"
          element={isAuthenticated ? <Dashboard /> : <Navigate to="/login" />}
        >
          <Route index element={<Center />} />
          <Route path="profile" element={<Profile />} />
          <Route path="plan-journey" element={<CreateTrip />} />
          <Route path="schedule" element={<Schedule />} />
          <Route path="trips" element={<Trips />} />
          <Route path="billing" element={<Billing />} />
        </Route>

        <Route path="*" element={<Error />} />
      </Routes>
    </div>
  );
}
