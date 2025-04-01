import React from 'react';
import './Schedule.css';
import LoadingSpinner from "../loading/LoadingSpinner";
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import plLocale from '@fullcalendar/core/locales/pl';
import { useTrip } from '../../../context/useTrip';

import '@fullcalendar/daygrid/main.css';

export default function Schedule() {

  const addOneDay = dateStr => {
    const date = new Date(dateStr);
    date.setDate(date.getDate() + 1);
    return date.toISOString().split('T')[0];
  };
  
  const { trips, loading } = useTrip();

  if (loading) return <LoadingSpinner message="Ładowanie kalendarza..." />;

  const formattedEvents = trips.map(trip => ({
    title: trip.tripName,
    start: trip.startDate,
    end: addOneDay(trip.endDate),
    allDay: true,
    backgroundColor: '#E0E7FF',
    borderColor: '#007BFF',
  }));

  return (
    <div style={{ padding: '2rem' }}>
      <h2 style={{ marginBottom: '1rem' }}>Twój terminarz podróży</h2>
      <FullCalendar
        plugins={[dayGridPlugin]}
        initialView="dayGridMonth"
        height="auto"
        events={formattedEvents}
        locale={plLocale}
        firstDay={1}
        headerToolbar={{
          left: 'prev,next today',
          center: 'title',
          right: ''
        }}
      />
    </div>
  );
}
