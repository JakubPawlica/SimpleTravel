import React from 'react';
import { useTrip } from '../../../context/useTrip';
import { useAuth } from '../../../context/useAuth';
import { toast } from 'react-toastify';
import { useNavigate } from "react-router-dom";
import { TbMoodSad } from "react-icons/tb";
import './Trips.css';

export default function Trips() {
  const { trips, loading, refreshTrips } = useTrip();
  const { user } = useAuth();
  const navigate = useNavigate();

  const handleAddTrip = () => {
    navigate("/dashboard/plan-journey");
  };

  const handleDelete = async (tripId) => {
    try {
      const res = await fetch(`http://localhost:8080/api/trips/${tripId}`, {
        method: 'DELETE',
        credentials: 'include',
      });

      if (!res.ok) {
        throw new Error('Usuwanie nie powiodło się');
      }

      toast.success('🗑️ Podróż została usunięta');
      refreshTrips();
    } catch (err) {
      console.error('Błąd przy usuwaniu:', err);
      toast.error('Nie udało się usunąć podróży.');
    }
  };

  if (loading) return <p>Ładowanie podróży...</p>;

  return (
    <div className="trips-page">
      {trips.length === 0 ? (
        <p className="blank-trip-page">
          <p className="blank-trip-page-icon"><TbMoodSad /></p>
          <p>Nie masz jeszcze żadnych podróży.</p>
          <button onClick={handleAddTrip} className="black-trip-page-button">Zaplanuj</button>
        </p>
      ) : (
        <div className="trip-card-grid">
          {trips.map(trip => (
            <div key={trip.id} className="trip-card">
              <h3>{trip.tripName}</h3>
              <p>📍 {trip.destination}</p>
              <p>🗓 {trip.startDate} - {trip.endDate}</p>
              <p>{trip.description}</p>

              {trip.createdBy?.id === user?.id && (
                <button
                  className="delete-trip-btn"
                  onClick={() => handleDelete(trip.id)}
                >
                  🗑 Usuń
                </button>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
