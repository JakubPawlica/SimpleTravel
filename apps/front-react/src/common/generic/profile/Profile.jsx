import React, { useState } from 'react';
import { useAuth } from "../../../context/useAuth";
import { FaRegUser } from "react-icons/fa";
import UserPP from "../../../assets/user_pp.jpg"
import { toast } from 'react-toastify';
import { FaEdit } from "react-icons/fa";
import "./Profile.css";

export default function Profile() {
  const { user, login } = useAuth();

  const [isEditing, setIsEditing] = useState(false);
  const [formData, setFormData] = useState({
    name: user.name,
    email: user.email
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };
  
  const handleCancel = () => {
    setFormData({ name: user.name, email: user.email });
    setIsEditing(false);
  };
  
  const handleSave = async () => {
    try {
      const res = await fetch(`http://localhost:8080/api/users/${user.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({
          name: formData.name,
          email: formData.email
        })
      });
  
      if (!res.ok) {
        throw new Error('Błąd podczas aktualizacji danych');
      }
  
      const updatedUser = await res.json();
  
      toast.success('Dane zostały zaktualizowane!');
      setIsEditing(false);
      login(updatedUser);
    } catch (err) {
      console.error(err);
      toast.error('Nie udało się zapisać zmian');
    }
  };  

  if (!user) return <p>Brak danych użytkownika.</p>;

  return (
      <div className="profile-card">
      <h2>Profil użytkownika</h2>
    {isEditing ? (
      <div className="profile-container">
        <div className="profile-container-picture">
          <img src={ UserPP } alt="profile_picture" className="profile-container-pp"/>
        </div>
        <div className="profile-container-info">
          <label>
            Imię:
            <input
              name="name"
              value={formData.name}
              onChange={handleChange}
            />
          </label>
          <label>
            Email:
            <input
              name="email"
              value={formData.email}
              onChange={handleChange}
            />
          </label>
          <div className="profile-buttons">
            <button onClick={handleSave}>Zapisz</button>
            <button onClick={handleCancel}>Anuluj</button>
          </div>
        </div> 
      </div>
    ) : (
      <div className = "profile-container">
        <div className="profile-container-picture">
          <img src={ UserPP } alt="profile_picture" className="profile-container-pp"/>
        </div>
        <div className="profile-container-info">
          <p><strong>Imię:</strong> {user.name}</p>
          <p><strong>Email:</strong> {user.email}</p>
          <button onClick={() => setIsEditing(true)} className="edit-profile-btn">
          <FaEdit /> Edytuj profil
          </button>
        </div>  
      </div>
    )}
  </div>
  );
}
