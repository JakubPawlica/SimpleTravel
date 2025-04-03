import React, { useState } from 'react';
import LoadingSpinner from "../loading/LoadingSpinner";
import { useTrip } from '../../../context/useTrip';
import { useAuth } from '../../../context/useAuth';
import { toast } from 'react-toastify';
import { useNavigate } from "react-router-dom";
import { TbMoodSad } from "react-icons/tb";
import { TbCalendarTime } from "react-icons/tb";
import { FaLocationDot } from "react-icons/fa6";
import { IoRefresh } from "react-icons/io5";
import './Trips.css';

export default function Trips() {
  const { trips, loading, refreshTrips } = useTrip();
  const { user } = useAuth();
  const navigate = useNavigate();

  const [refreshSuccess, setRefreshSuccess] = useState(false);

  const handleManualRefresh = async () => {
    try {
      await refreshTrips();
      toast.success('Lista podróży została odświeżona!');
    } catch (err) {
      console.error('Błąd odświeżania:', err);
      toast.error('Nie udało się odświeżyć podróży.');
    }
  };  

  const handleAddTrip = () => {
    navigate("/dashboard/plan-journey");
  };

  const formatDate = (isoDate) => {
    return new Intl.DateTimeFormat('pl-PL').format(new Date(isoDate));
  };

  if (loading) return <LoadingSpinner message="Ładowanie podróży..." />;

  return (
    <div className="trips-page">
      {trips.length === 0 ? (
        <p className="blank-trip-page">
          <p className="blank-trip-page-icon"><TbMoodSad /></p>
          <p>Nie masz jeszcze żadnych podróży.</p>
          <div class="blank-trip-page-btn-section">
            <button onClick={handleAddTrip} className="black-trip-page-button">Zaplanuj</button>
            <button onClick={handleManualRefresh} className="black-trip-page-button">
              <IoRefresh class="refresh-trips-btn-icon"/><p>Odśwież podróże</p>
            </button>
          </div>
        </p>
      ) : (
        <div className="trip-card-grid">
          <button onClick={handleManualRefresh} className="refresh-trips-btn">
            <IoRefresh class="refresh-trips-btn-icon"/><p>Odśwież podróże</p>
          </button>
          {trips.map(trip => (
            <div key={trip.id} className="trip-card" onClick={() => navigate(`/dashboard/trips/${trip.id}`)}>
              <h3 className="trip-card-title">{trip.tripName}</h3>
              <p className="trip-card-info"><FaLocationDot className="trip-card-icon"/> {trip.destination}</p>
              <p className="trip-card-info"><TbCalendarTime className="trip-card-icon"/> {formatDate(trip.startDate)} - {formatDate(trip.endDate)}</p>
              <p className="trip-card-desc">Opis podróży: {trip.description}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
