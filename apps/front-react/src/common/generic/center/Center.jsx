import React from "react";
import "./Center.css";
import { useAuth } from "../../../context/useAuth";

export default function Center() {
  const { user } = useAuth();

  return (
    <div className="center">
      <h1>Witaj, {user?.name || "ponownie"}</h1>
    </div>
  );
}
