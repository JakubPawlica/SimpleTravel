import React, { useEffect, useState } from "react";
import "./Dashboard.css";
import Sidebar from "../generic/sidebar/Sidebar";
import { Outlet } from "react-router-dom";

export default function Dashboard() {
  return(
    <div className="dashboard">
      <Sidebar />
      <main className="dashboard-content">
        <Outlet />
      </main>
    </div>
  );
}