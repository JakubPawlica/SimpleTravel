import React, { useState } from 'react';
import './Login.css'; 
import { useNavigate } from 'react-router-dom';
import { useAuth } from "../../../context/useAuth";
import { useEffect } from "react";
import googleLogo from '../../../assets/google-icon.png';

function Login() {

  const { isAuthenticated } = useAuth();
  const { login } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (isAuthenticated) {
      navigate('/dashboard');
    }
  }, [isAuthenticated, navigate]);

  const [formData, setFormData] = useState({ email: '', password: '' });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch('http://localhost:8080/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify(formData)
      });
  
      const result = await response.json();
  
      if (response.ok) {
        const me = await fetch('http://localhost:8080/api/me', {
          credentials: 'include',
        });
        const user = await me.json();
        login(user);
        navigate('/dashboard');
      } else {
        alert(result.error || 'Niepoprawne dane logowania');
      }
    } catch (err) {
      console.error('Błąd przy logowaniu:', err);
      alert('Błąd połączenia z serwerem');
    }
  };

  return (
    <div className="login-background">
      <div className="entry-form">
        <div className="home-logo"><span className="material-symbols-outlined">travel</span><span className="simple">Simple</span><span className="travel">Travel</span></div>
        <form onSubmit={handleSubmit}>
          <input name="email" placeholder="Email" type="email" value={formData.email} onChange={handleChange} required/><br /><br />
          <input name="password" placeholder="Hasło" type="password" value={formData.password} onChange={handleChange} required/><br /><br />
          <button type="submit">Zaloguj się</button>
        </form>
        <br />
        <p>Nie masz konta?<button onClick={() => navigate('/signup')}>Zarejestruj się</button></p>
        <p>────── albo ──────</p>
        <button className="button-signup-google"><img className="google-logo" src={googleLogo} alt="Logo Google"/> Zaloguj się z Google</button>
      </div>
    </div>
  );
}

export default Login;
