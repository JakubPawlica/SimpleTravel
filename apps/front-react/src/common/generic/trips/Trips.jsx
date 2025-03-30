import React from 'react';
import { useTrip } from '../../../context/useTrip';
import { useAuth } from '../../../context/useAuth';
import { toast } from 'react-toastify';
import { useNavigate } from "react-router-dom";
import { TbMoodSad } from "react-icons/tb";
import { RiDeleteBin5Line } from "react-icons/ri";
import { TbCalendarTime } from "react-icons/tb";
import { FaLocationDot } from "react-icons/fa6";
import './Trips.css';

export default function Trips() {
  const { trips, loading, refreshTrips } = useTrip();
  const { user } = useAuth();
  const navigate = useNavigate();

  const handleAddTrip = () => {
    navigate("/dashboard/plan-journey");
  };

  const formatDate = (isoDate) => {
    return new Intl.DateTimeFormat('pl-PL').format(new Date(isoDate));
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
            <div key={trip.id} className="trip-card" onClick={() => navigate(`/dashboard/trips/${trip.id}`)}>
              <h3 className="trip-card-title">{trip.tripName}</h3>
              <p className="trip-card-info"><FaLocationDot className="trip-card-icon"/> {trip.destination}</p>
              <p className="trip-card-info"><TbCalendarTime className="trip-card-icon"/> {formatDate(trip.startDate)} - {formatDate(trip.endDate)}</p>
              <p className="trip-card-desc">Opis podróży: {trip.description}</p>

              {trip.createdBy?.id === user?.id && (
                <button
                  className="delete-trip-btn"
                  onClick={() => handleDelete(trip.id)}
                >
                  <RiDeleteBin5Line /> Usuń
                </button>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
