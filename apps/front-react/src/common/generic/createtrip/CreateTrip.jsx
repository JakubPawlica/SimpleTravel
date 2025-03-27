import React, { useState } from "react";
import { DateRange } from 'react-date-range';
import { addDays } from 'date-fns';
import { pl } from 'date-fns/locale';
import 'react-date-range/dist/styles.css'; 
import 'react-date-range/dist/theme/default.css';
import "./CreateTrip.css";
import { toast } from "react-toastify";
import { PiPlant } from "react-icons/pi";

export default function CreateTrip() {
  const [step, setStep] = useState(1);
  const [trip, setTrip] = useState({
    name: "",
    startDate: "",
    endDate: "",
    destination: "",
    members: [],
  });
  
  const handleChange = (e) => {
    setTrip({ ...trip, [e.target.name]: e.target.value });
  };

  const next = () => {
    if (step === 1 && !trip.name.trim()) {
      toast.warning("Wpisz nazwę podróży, zanim przejdziesz dalej!");
      return;
    }
    if (step === 3 && !trip.destination.trim()) {
      toast.warning("Wpisz miejsce docelowe, zanim przejdziesz dalej!");
      return;
    }
  
    setStep((prev) => Math.min(prev + 1, 4));
  };
  const back = () => setStep((prev) => Math.max(prev - 1, 1));

  const handleSubmit = (e) => {
    e.preventDefault();
    toast.success("🎉 Podróż zaplanowana!");
    console.log(trip);
  };

  return (
    <div className="create-trip-multistep">
      <h2><PiPlant className="plant-icon"/> Zaplanuj swoją podróż</h2>
      <ProgressBar step={step} />

      <form className="create-trip-multistep__form" onSubmit={handleSubmit}>
        {/* Krok 1: Nazwa */}
        {step === 1 && (
          <div className="create-trip-multistep__step">
            <label>Nazwa podróży:</label>
            <input
              name="name"
              value={trip.name}
              onChange={handleChange}
              required
            />
          </div>
        )}

        {/* Krok 2: Daty */}
        {step === 2 && (
          <div className="create-trip-multistep__step">
            <p>📆 Wybierz daty podróży:</p>
            <DateRange
              locale={pl}
              ranges={[{
                startDate: trip.startDate ? new Date(trip.startDate) : new Date(),
                endDate: trip.endDate ? new Date(trip.endDate) : addDays(new Date(), 7),
                key: 'selection'
              }]}
              onChange={(ranges) => {
                const { startDate, endDate } = ranges.selection;
                setTrip({
                  ...trip,
                  startDate: startDate.toLocaleDateString('sv-SE'),
                  endDate: endDate.toLocaleDateString('sv-SE'),
                });
              }}
              moveRangeOnFirstSelection={false}
              months={2}
              direction="horizontal"
              showMonthAndYearPickers={true}
              minDate={new Date()}
              rangeColors={["#007bff"]}
            />
          </div>
        )}

        {/* Krok 3: Miejsce */}
        {step === 3 && (
          <div className="create-trip-multistep__step">
            <label>Miejsce docelowe:</label>
            <input
              name="destination"
              value={trip.destination}
              onChange={handleChange}
              required
            />
          </div>
        )}

        {/* Krok 4: Członkowie */}
        {step === 4 && (
          <div className="create-trip-multistep__step">
            <p>🔒 Funkcja zapraszania znajomych będzie dostępna wkrótce.</p>
          </div>
        )}

        <div className="create-trip-multistep__controls">
          {step > 1 && (
            <button
              type="button"
              onClick={back}
              className="create-trip-multistep__button"
            >
              Wstecz
            </button>
          )}
          {step < 4 && (
            <button
              type="button"
              onClick={next}
              className="create-trip-multistep__button"
            >
              Dalej
            </button>
          )}
          {step === 4 && (
            <button type="submit" className="create-trip-multistep__button">
              Zatwierdź
            </button>
          )}
        </div>
      </form>
    </div>
  );
}

function ProgressBar({ step }) {
  const steps = [
    { id: 1, title: "Nazwa", desc: "Utwórz nazwę podróży" },
    { id: 2, title: "Data", desc: "Zaplanuj czas podróży" },
    { id: 3, title: "Miejsce", desc: "Wybierz destynację" },
    { id: 4, title: "Członkowie", desc: "Zaproś znajomych" },
  ];

  return (
    <div className="create-trip-multistep__progress">
      {steps.map((s) => (
        <div
          key={s.id}
          className={`create-trip-multistep__step-indicator ${
            step === s.id ? "active" : ""
          } ${step > s.id ? "done" : ""}`}
        >
          <div className="create-trip-multistep__circle">
            {step > s.id ? "✓" : s.id}
          </div>
          <div className="create-trip-multistep__step-info">
            <strong>{s.title}</strong>
            <p>{s.desc}</p>
          </div>
        </div>
      ))}
    </div>
  );
}
