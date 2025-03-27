import React from "react";
import "./Error.css";
import { useNavigate } from "react-router-dom";
import Button from "react-bootstrap/Button";

export default function Error() {
  let navigate = useNavigate();

  return (
    <div className="container ">
      <div className="row justify-content-center">
        <div>
          <h1>Error 404: Nie udało się znaleźć strony</h1>
        </div>
        <div>
          <Button size="sm" onClick={() => navigate("/")}>
            Powrót do SimpleTravel
          </Button>
        </div>
      </div>
    </div>
  );
}
