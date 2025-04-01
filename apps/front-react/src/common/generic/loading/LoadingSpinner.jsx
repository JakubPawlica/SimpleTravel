import React from 'react';
import './LoadingSpinner.css';

export default function LoadingSpinner({ message = 'Ładowanie...' }) {
  return (
    <div className="spinner-container">
      <div className="loading-spinner"></div>
      <p>{message}</p>
    </div>
  );
}