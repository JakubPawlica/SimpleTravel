import React from "react";
import { NavLink } from "react-router-dom";
import { FaUser } from "react-icons/fa";
import { IoIosCreate } from "react-icons/io";
import { FaCalendarAlt } from "react-icons/fa";
import { IoMdChatbubbles } from "react-icons/io";
import { MdOutlineTravelExplore } from "react-icons/md";
import { MdAttachMoney } from "react-icons/md";
import { AiFillHome } from "react-icons/ai";
import { useAuth } from "../../../context/useAuth";
import { useNavigate } from "react-router-dom";
import "./Sidebar.css";

export default function Sidebar() {

  const { logout, user } = useAuth();
  const navigate = useNavigate();

  return (
    <aside className="sidebar">
      <div className="sidebar-header">
        <div className="home-logo"><span className="material-symbols-outlined">travel</span><span className="simple">Simple</span><span className="travel">Travel</span></div>
      </div>
      <nav className="sidebar-nav">
        <NavLink
          to="/dashboard"
          className={({ isActive }) =>
            isActive ? "sidebar-link active" : "sidebar-link"
          }
        >
          <AiFillHome />
          Strona główna
        </NavLink>
        <NavLink
          to="/profile"
          className={({ isActive }) =>
            isActive ? "sidebar-link active" : "sidebar-link"
          }
        >
          <FaUser />
          Profil
        </NavLink>
        <NavLink
          to="/plan-journey"
          className={({ isActive }) =>
            isActive ? "sidebar-link active" : "sidebar-link"
          }
        >
          <IoIosCreate />
          Planuj podróż
        </NavLink>
        <NavLink
          to="/schedule"
          className={({ isActive }) =>
            isActive ? "sidebar-link active" : "sidebar-link"
          }
        >
          <FaCalendarAlt />
          Terminarz
        </NavLink>
        <NavLink
          to="/chats"
          className={({ isActive }) =>
            isActive ? "sidebar-link active" : "sidebar-link"
          }
        >
          <IoMdChatbubbles />
          Moje rozmowy
        </NavLink>
        <NavLink
          to="/trips"
          className={({ isActive }) =>
            isActive ? "sidebar-link active" : "sidebar-link"
          }
        >
          <MdOutlineTravelExplore />
          Moje podróże
        </NavLink>
        <NavLink
          to="/billing"
          className={({ isActive }) =>
            isActive ? "sidebar-link active" : "sidebar-link"
          }
        >
          <MdAttachMoney />
          Rozliczenia
        </NavLink>
      </nav>

      {user && (
      <div className="sidebar-logout">
        <button className="logout-button" onClick={() => {
          logout();
          navigate("/login");
        }}>
          Wyloguj się
        </button>
      </div>
      )}

    </aside>
  );
}
