import React from 'react';
import { useTrip } from '../../../context/useTrip';
import './Trips.css';

export default function Trips() {
  const { trips, loading } = useTrip();

  if (loading) return <p>Ładowanie podróży...</p>;

  return (
    <div className="trips-page">
      {trips.length === 0 ? (
        <p>Nie masz jeszcze żadnych podróży.</p>
      ) : (
        <div className="trip-card-grid">
          {trips.map(trip => (
            <div key={trip.id} className="trip-card">
              <h3>{trip.tripName}</h3>
              <p>📍 {trip.destination}</p>
              <p>
                🗓 {trip.startDate} - {trip.endDate}
              </p>
              <p>{trip.description}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
