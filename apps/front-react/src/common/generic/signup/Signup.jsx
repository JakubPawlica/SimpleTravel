import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from "../../../context/useAuth";
import { useEffect } from "react";
import { toast } from 'react-toastify';
import "./Signup.css";
import googleLogo from '../../../assets/google-icon.png';

function Signup() {

  const { isAuthenticated } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (isAuthenticated) {
      navigate('/dashboard');
    }
  }, [isAuthenticated, navigate]);

  const [formData, setFormData] = useState({ 
    username: '',
    email: '',
    password: ''
  });

  const [emailError, setEmailError] = useState(false);
  const [passwordError, setPasswordError] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!isValidEmail(formData.email)) {
      setEmailError(true);
      toast.warning('Podaj poprawny adres e-mail');
      return;
    } else {
      setEmailError(false);
    }

    if (formData.password.length < 3) {
      setPasswordError(true);
      toast.warning('Hasło musi mieć minimum 3 znaki');
      return;
    } else {
      setPasswordError(false);
    }

    try {
      const response = await fetch('http://localhost:8080/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify({
          name: formData.username,
          email: formData.email,
          password: formData.password
        })
      });
  
      const result = await response.json();
  
      if (response.ok) {
        toast.success('✈️ Rejestracja przebiegła pomyślnie!');
        navigate('/login');
      } else {
        toast.error(result.error || 'Coś poszło nie tak przy rejestracji');
        setFormData({ email: '' });
      }
    } catch (err) {
      console.error('Błąd przy rejestracji:', err);
      toast.error('Błąd połączenia z serwerem');
    }
  };

  return (
    <div className="signup-background">
      <div className="entry-form">
        <div className="home-logo"><span className="material-symbols-outlined">travel</span><span className="simple">Simple</span><span className="travel">Travel</span></div>
        <form onSubmit={handleSubmit}>
          <input name="username" placeholder="Nazwa użytkownika" value={formData.username} onChange={handleChange} required /><br /><br />
          <input name="email" placeholder="Email" type="email" value={formData.email} onChange={handleChange} onBlur={() => setEmailError(!isValidEmail(formData.email))} required />
          {emailError && (<p className="input-error-message">Niepoprawny adres e-mail</p>)}<br /><br />
          <input name="password" placeholder="Hasło" type="password" value={formData.password} onChange={handleChange} onBlur={() => setPasswordError(formData.password.length < 3)} required />
          {passwordError && (<p className="input-error-message">Hasło musi mieć minimum 3 znaki</p>)}<br /><br />
          <button type="submit">Zarejestruj się</button>
        </form>
        <br />
        <p>Masz już konto?<button onClick={() => navigate('/login')}>Zaloguj się</button></p>
        <p>────── albo ──────</p>
        <button className="button-signup-google"><img className="google-logo" src={googleLogo} alt="Logo Google"/> Zarejestruj się z Google</button>
      </div>
    </div>
  );
}

export default Signup;
