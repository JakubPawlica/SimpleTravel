import { createContext, useEffect, useState } from 'react';

export const ScheduleContext = createContext(null);

export const ScheduleProvider = ({ children }) => {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchSchedule = async () => {
      try {
        const res = await fetch('http://localhost:8080/api/schedule', {
          credentials: 'include',
        });
        if (res.ok) {
          const data = await res.json();
          setEvents(data);
        } else {
          console.error('Nie udało się pobrać danych z /api/schedule');
        }
      } catch (err) {
        console.error('Błąd pobierania harmonogramu:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchSchedule();
  }, []);

  return (
    <ScheduleContext.Provider value={{ events, loading }}>
      {children}
    </ScheduleContext.Provider>
  );
};
