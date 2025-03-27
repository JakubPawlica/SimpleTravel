import React from "react";
import { useAuth } from "../../../context/useAuth";
import { FaRegUser } from "react-icons/fa";
import "./Profile.css";

export default function Profile() {
  const { user } = useAuth();

  if (!user) return <p>Brak danych użytkownika.</p>;

  return (
    <div className="profile-container">
      <h2><FaRegUser className="user-icon"/> Twój profil</h2>
      <div className="profile-card">
        <p><strong>Imię:</strong> {user.name}</p>
        <p><strong>Email:</strong> {user.email}</p>
      </div>
    </div>
  );
}
