// MenuContent.js
import React, { useEffect } from 'react';
import { Link } from 'react-router-dom';
import './ThreePointLeaderMenu.css';

const MenuContent = ({ isOpen }) => {
  useEffect(() => {
    const userDeleteBtn = document.querySelector("#user-delete-btn");
    const userDeleteDialog = document.querySelector("#user-delete-dialog");

    const openDialog = () => {
      if (userDeleteDialog) {
        userDeleteDialog.showModal();
      }
    };

    if (userDeleteBtn) {
      userDeleteBtn.addEventListener("click", openDialog);
    }

    return () => {
      if (userDeleteBtn) {
        userDeleteBtn.removeEventListener("click", openDialog);
      }
    };
  }, []);

  return (
    <nav className={`nav-menu ${isOpen ? 'open' : ''}`}>
      <ul>
        <li><Link to="#" id="user-delete-btn">User削除</Link></li>
        <li><Link to="#about">About</Link></li>
        <li><Link to="#services">Services</Link></li>
        <li><Link to="#contact">Contact</Link></li>
      </ul>
    </nav>
  );
};

export default MenuContent;
