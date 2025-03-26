import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Login.css'; 
import googleLogo from '../../../assets/google-icon.png';

function Login() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({ email: '', password: '' });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Zalogowano:', formData);
    // Tu możesz dodać żądanie do API do logowania
  };

  return (
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
  );
}

export default Login;
