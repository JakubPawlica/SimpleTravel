import React, { useEffect, useState } from 'react';
import { useNavigate } from "react-router-dom";
import { useParams } from 'react-router-dom';
import { TbArrowBackUp } from "react-icons/tb";
import './TripDetails.css';

export default function TripDetails() {
  const { id } = useParams();
  const [trip, setTrip] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  const handleGoBackToTrips = () => {
    navigate("/dashboard/trips");
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
      <h2>{trip.tripName}</h2>
      <p><strong>Miejsce:</strong> {trip.destination}</p>
      <p><strong>Termin:</strong> {trip.startDate.slice(0, 10)} – {trip.endDate.slice(0, 10)}</p>
      <p><strong>Opis:</strong> {trip.description}</p>
      <p><strong>Autor:</strong> {trip.createdBy?.name}</p>
      <button onClick={handleGoBackToTrips}><TbArrowBackUp /></button>
    </div>
  );
}
