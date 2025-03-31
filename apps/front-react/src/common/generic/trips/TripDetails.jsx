import React, { useEffect, useState } from 'react';
import { useNavigate } from "react-router-dom";
import { useParams } from 'react-router-dom';
import { TbArrowBackUp } from "react-icons/tb";
import './TripDetails.css';
import { FaMapLocationDot } from "react-icons/fa6";
import { LuCalendarClock } from "react-icons/lu";
import { FaInfoCircle } from "react-icons/fa";
import { FaCrown } from "react-icons/fa6";

export default function TripDetails() {
  const { id } = useParams();
  const [trip, setTrip] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  const handleGoBackToTrips = () => {
    navigate("/dashboard/trips");
  };

  const handleGoToSchedule = () => {
    navigate("/dashboard/schedule");
  };

  useEffect(() => {
    const fetchTrip = async () => {
      try {
        const res = await fetch(`http://localhost:8080/api/trips/${id}`, {
          credentials: 'include'
        });
        if (!res.ok) throw new Error('Błąd pobierania podróży');
        const data = await res.json();
        setTrip(data);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    fetchTrip();
  }, [id]);

  if (loading) return <p>Ładowanie szczegółów podróży...</p>;
  if (!trip) return <p>Podróż nie została znaleziona.</p>;

  return (
    <div className="trip-details">
      <div className="trip-details-header"></div>
      <div className="trip-details-main">
        <h2 className="trip-details-title">{trip.tripName}</h2>
        <div className="trip-details-info">
          <p><strong><FaMapLocationDot className="trip-details-icon"/> Miejsce:</strong> {trip.destination}</p>
          <p><strong><LuCalendarClock className="trip-details-icon"/> Termin:</strong> {trip.startDate.slice(0, 10)} – {trip.endDate.slice(0, 10)}</p>
          <p><strong><FaInfoCircle className="trip-details-icon"/> Opis:</strong> {trip.description}</p>
          <p><strong><FaCrown className="trip-details-icon"/> Organizator:</strong> {trip.createdBy?.name}</p>
        </div>
        <div class="trip-details-buttons">
          <button onClick={handleGoBackToTrips}><TbArrowBackUp /></button>
          <button onClick={handleGoToSchedule}><LuCalendarClock className="trip-details-button-calendar"/></button>
        </div>
      </div>
    </div>
  );
}
