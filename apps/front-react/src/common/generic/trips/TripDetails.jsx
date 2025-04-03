import React, { useEffect, useState } from 'react';
import LoadingSpinner from "../loading/LoadingSpinner";
import { useTrip } from '../../../context/useTrip';
import { useAuth } from '../../../context/useAuth';
import { useNavigate } from "react-router-dom";
import { useParams } from 'react-router-dom';
import { TbArrowBackUp } from "react-icons/tb";
import { toast } from 'react-toastify';
import './TripDetails.css';
import { FaMapLocationDot } from "react-icons/fa6";
import { LuCalendarClock } from "react-icons/lu";
import { FaInfoCircle } from "react-icons/fa";
import { FaCrown } from "react-icons/fa6";
import { RiDeleteBin5Line } from "react-icons/ri";
import { FaEdit } from "react-icons/fa";

export default function TripDetails() {

  const { refreshTrips } = useTrip();
  const { user } = useAuth();

  const { id } = useParams();
  const [trip, setTrip] = useState(null);
  const [loading, setLoading] = useState(true);
  const [showEditModal, setShowEditModal] = useState(false);
  const [editedTrip, setEditedTrip] = useState(null);
  const navigate = useNavigate();

  const handleGoBackToTrips = () => {
    navigate("/dashboard/trips");
  };

  const handleGoToSchedule = () => {
    navigate("/dashboard/schedule");
  };

  const handleEditSubmit = async (e) => {
    e.preventDefault();

    if (new Date(editedTrip.startDate) > new Date(editedTrip.endDate)) {
      toast.warning("Data zakończenia nie może być wcześniejsza niż data rozpoczęcia.");
      return;
    }    
  
    try {
      const res = await fetch(`http://localhost:8080/api/trips/${trip.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          tripName: editedTrip.tripName,
          destination: editedTrip.destination,
          description: editedTrip.description,
          start_date: editedTrip.startDate,
          end_date: editedTrip.endDate,
        })
      });
  
      if (!res.ok) throw new Error('Błąd edycji podróży');
  
      const updated = await res.json();
      setTrip(updated);
      refreshTrips();
      toast.success('✏️ Podróż została zaktualizowana!');
      setShowEditModal(false);
    } catch (err) {
      console.error(err);
      toast.error('Nie udało się zapisać zmian.');
    }
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
      navigate("/dashboard/trips");
    } catch (err) {
      console.error('Błąd przy usuwaniu:', err);
      toast.error('Nie udało się usunąć podróży.');
    }
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

  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'Escape') setShowEditModal(false);
    };
  
    if (showEditModal) {
      window.addEventListener('keydown', handleKeyDown);
    }
  
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [showEditModal]);  

  if (loading) return <LoadingSpinner message="Ładowanie szczegółów podróży..." />;
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
        <div className="trip-details-buttons">
          <div className="trip-details-default-btns">
            <button onClick={handleGoBackToTrips}><TbArrowBackUp /></button>
            <button onClick={handleGoToSchedule}><LuCalendarClock className="trip-details-button-calendar"/></button>
          </div>
          <div className="trip-details-admin-btns">
            {trip && (
              <button onClick={() => {
                setEditedTrip({
                  tripName: trip.tripName,
                  destination: trip.destination,
                  description: trip.description,
                  startDate: trip.startDate,
                  endDate: trip.endDate
                });
                setShowEditModal(true);
              }} className="trip-details-edit-btn">
                <FaEdit />
              </button>
            )}
            {trip.createdBy?.id === user?.id && (
                <button
                  className="delete-trip-btn"
                  onClick={() => handleDelete(trip.id)}
                >
                  <RiDeleteBin5Line /></button>
            )}
          </div>
        </div>
      </div>
      
      {showEditModal && editedTrip && (
        <div className="modal-backdrop" onClick={() => setShowEditModal(false)}>
          <div className="modal" onClick={(e) => e.stopPropagation()}>
            <h3>Edytuj podróż</h3>
            <form onSubmit={handleEditSubmit}>
              <input
                type="text"
                value={editedTrip.tripName}
                onChange={(e) => setEditedTrip({ ...editedTrip, tripName: e.target.value })}
                placeholder="Nazwa podróży"
                required
              />
              <input
                type="text"
                value={editedTrip.destination}
                onChange={(e) => setEditedTrip({ ...editedTrip, destination: e.target.value })}
                placeholder="Cel"
                required
              />
              <input
                type="date"
                value={editedTrip.startDate?.slice(0, 10) || ''}
                onChange={(e) =>
                  setEditedTrip({ ...editedTrip, startDate: e.target.value })
                }
                required
              />
              <input
                type="date"
                value={editedTrip.endDate?.slice(0, 10) || ''}
                onChange={(e) =>
                  setEditedTrip({ ...editedTrip, endDate: e.target.value })
                }
                required
              />
              <textarea
                value={editedTrip.description}
                onChange={(e) => setEditedTrip({ ...editedTrip, description: e.target.value })}
                placeholder="Opis"
                rows={3}
              />
              <div className="modal-buttons">
                <button type="submit">Zapisz</button>
                <button type="button" onClick={() => setShowEditModal(false)}>Anuluj</button>
              </div>
            </form>
          </div>
        </div>
      )}  

    </div>
  );
}
