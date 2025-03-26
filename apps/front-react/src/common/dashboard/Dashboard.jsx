import React, { useEffect, useState } from "react";
import "./Dashboard.css";
import Sidebar from "../generic/sidebar/Sidebar";
import Center from "../generic/center/Center";

export default function Dashboard() {
  return(
    <div className="dashboard">
      <Sidebar />
      <Center />
    </div>
  );
}