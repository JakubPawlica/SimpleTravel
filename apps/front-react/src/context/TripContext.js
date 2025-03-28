import { createContext, useEffect, useState } from 'react';

export const TripContext = createContext(null);

export const TripProvider = ({ children }) => {
  const [trips, setTrips] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchTrips = async () => {
    try {
      const res = await fetch('http://localhost:8080/api/trips', {
        credentials: 'include',
      });
      if (res.ok) {
        const data = await res.json();
        setTrips(data);
      } else {
        console.error("Nie udało się pobrać podróży");
      }
    } catch (err) {
      console.error("Błąd podczas pobierania podróży:", err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchTrips();
  }, []);

  return (
    <TripContext.Provider value={{ trips, loading, refreshTrips: fetchTrips }}>
      {children}
    </TripContext.Provider>
  );
};
