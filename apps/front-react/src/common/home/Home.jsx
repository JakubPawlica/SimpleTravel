import React, { useState } from "react";
import { NavLink } from "react-router-dom";
import Product from "../product/Product";
import Counter from "../../features/counter/counter";
import { AXIOS } from "../../app/axios-http";
import logo from "../../assets/svg/logo.svg";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { useDispatch } from "react-redux";
import { login, logout } from "../../features/auth/authSlice";
import { useSelector } from "react-redux";
import "./Home.css";
import Button from "react-bootstrap/Button";

export default function Home() {
  return (
    <div className="home-container">
      <header className="home-header">
        <div className="home-logo">SimpleTravel</div>
        <div className="home-auth">
          <NavLink to="/login" className="home-auth-button">
            Zaloguj / Zarejestruj się
          </NavLink>
        </div>
      </header>
      <main className="home-main">
        <div className="hero-content">
          <h1 className="hero-title">Odkryj nowe przygody</h1>
          <p className="hero-subtitle">
            Zarządzaj, planuj, pozostań w kontakcie - wszystko w jednym miejscu.
          </p>
          <NavLink to="/explore" className="hero-cta-button">
            Rozpocznij podróż
          </NavLink>
        </div>
        <div className="hero-image-container">
          <img
            src="https://images01.nicepagecdn.com/c461c07a441a5d220e8feb1a/585d277d6df25b5f99f98670/jjjj-min.png"
            alt="Podróże"
            className="hero-image"
          />
        </div>
      </main>
    </div>
  );
}

  /*
  const dispatch = useDispatch();
  const { isAuthenticated } = useSelector((state) => state.auth);
  let [isRequesting, setRequesting] = useState(false);
  let navigate = useNavigate();

  const handleLoginTest = () => {
    const id = toast.loading("Please wait...");
    setRequesting(true);

    const loginPayload = {
      email: "admin@gmail.com",
      password: "admin",
    };

    AXIOS.post("/login_check", loginPayload)
      .then((response) => {
        toast.update(id, {
          render: "Login successfully !",
          type: "success",
          isLoading: false,
          autoClose: 3000,
          closeOnClick: true,
        });
        //get token from response
        const token = response.data.token;

        dispatch(login(token));

        //redirect user to dashboard page
        navigate("/dashboard");
      })
      .catch((err) => {
        toast.update(id, {
          render: err.response.data.message,
          type: "error",
          isLoading: false,
          autoClose: 3000,
          closeOnClick: true,
        });
        console.log(err);
      })
      .finally(() => setRequesting(false));
  };

  const handleLogoutTest = () => {
    dispatch(logout());
  };

  return (
    <div className="Home">
      <img src={logo} className="Home-logo" alt="logo" />
      <p className="text-sm">
        <small>
          Edit <code>src/app/App.tsx</code> and save to reload ok!
        </small>
      </p>
      <div className="container">
        <div className="row justify-content-center">
          <div>
            <h1>Test Skeleton</h1>
          </div>
        </div>
        <div className="row">
          <div className="col">
            <h2>Test Auth</h2>
            {isAuthenticated ? (
              <Button
                disabled={isRequesting}
                onClick={() => {
                  handleLogoutTest();
                }}
                variant="danger"
                size="sm"
              >
                Logout
              </Button>
            ) : (
              <Button
                disabled={isRequesting}
                onClick={() => {
                  handleLoginTest();
                }}
                size="sm"
              >
                Login
              </Button>
            )}
          </div>
          <Counter />
        </div>
        <Product />
      </div>
    </div>
  );*/
