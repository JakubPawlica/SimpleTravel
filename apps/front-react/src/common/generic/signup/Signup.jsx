import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import "./Signup.css";
import googleLogo from '../../../assets/google-icon.png';

function Signup() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({ username: '', email: '', password: '' });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

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
        console.log('Zarejestrowano:', result);
        navigate('/login');
      } else {
        alert(result.error || 'Coś poszło nie tak przy rejestracji');
      }
    } catch (err) {
      console.error('Błąd przy rejestracji:', err);
      alert('Błąd połączenia z serwerem');
    }
  };

  return (
    <div className="signup-background">
      <div className="entry-form">
        <div className="home-logo"><span className="material-symbols-outlined">travel</span><span className="simple">Simple</span><span className="travel">Travel</span></div>
        <form onSubmit={handleSubmit}>
          <input name="username" placeholder="Nazwa użytkownika" value={formData.username} onChange={handleChange} required /><br /><br />
          <input name="email" placeholder="Email" type="email" value={formData.email} onChange={handleChange} required /><br /><br />
          <input name="password" placeholder="Hasło" type="password" value={formData.password} onChange={handleChange} required /><br /><br />
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
