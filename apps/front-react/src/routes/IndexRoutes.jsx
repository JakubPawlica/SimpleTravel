import React from "react";
import { Routes, Route, Navigate } from "react-router-dom";
import { useSelector } from "react-redux";
import Home from "../common/home/Home";
import Dashboard from "../common/dashboard/Dashboard";
import Error from "../common/generic/error/Error";
import Login from "../common/generic/login/Login";
import Signup from "../common/generic/signup/Signup";
import Profile from "../common/generic/profile/Profile";
import Settings from "../common/generic/settings/Settings";
import HandleUsers from "../common/generic/admin/handle-users/HandleUsers";

export default function IndexRoutes() {
  const { isAuthenticated } = useSelector((state) => state.auth);

  //trzeba dodać blokadę wejścia na dashboard i zakładki bez logowania (usunąłem na chwilę)

  return (
    <div id="body-content">
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="login" element={<Login />} />
        <Route path="signup" element={<Signup />} />
        <Route path="dashboard" element={<Dashboard/>} />
        <Route path="profile" element={<Profile />} />
        <Route path="settings" element={<Settings />} />
        <Route path="handle-users" element={<HandleUsers />} />
        <Route path="*" element={<Error />} />
      </Routes>
    </div>
  );
}
