import React from "react";
import { NavLink } from "react-router-dom";
import { FaPlaneDeparture } from "react-icons/fa";
import { FaUser } from "react-icons/fa";
import { IoIosCreate } from "react-icons/io";
import { FaCalendarAlt } from "react-icons/fa";
import { IoMdChatbubbles } from "react-icons/io";
import { MdOutlineTravelExplore } from "react-icons/md";
import { MdAttachMoney } from "react-icons/md";
import { AiFillHome } from "react-icons/ai";
import "./Sidebar.css";

export default function Sidebar() {
  return (
    <aside className="sidebar">
      {/* Nagłówek sidebara z logo */}
      <div className="sidebar-header">
        <FaPlaneDeparture />
        <h1 className="logo">SimpleTravel</h1>
      </div>
      {/* Menu nawigacyjne */}
      <nav className="sidebar-nav">
        <NavLink
          to="/home"
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
    </aside>
  );
}
