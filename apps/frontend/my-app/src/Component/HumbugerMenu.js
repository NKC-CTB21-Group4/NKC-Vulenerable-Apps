// HumbugerMenu.js
import React, { useState, useEffect, useContext } from 'react';
import { Link } from 'react-router-dom';
import AuthContext from '../Utils/AuthProvider'
import './css/HumbugerMenu.css';
import UserDelete from './UserDelete';

const HumbugerMenu = () => {
  const [isOpen, setIsOpen] = useState(false);
  const {user} = useContext(AuthContext);
  const userid = user.id;

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  useEffect(() => {
    const userDeleteBtn = document.querySelector("#user-delete-btn");
    const userDeleteDialog = document.querySelector("#user-delete-dialog");

    const openDialog = () => {
      userDeleteDialog.showModal();
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
    <div>
      <div className={`hamburger-menu ${isOpen ? 'open' : ''}`} onClick={toggleMenu}>
        <div className='hambuger-line'></div>
        <div className='hambuger-line'></div>
        <div className='hambuger-line'></div>
      </div>
      <nav className={`nav-menu ${isOpen ? 'open' : ''}`}>
        <ul>
          <li><Link to="#" id="user-delete-btn">User削除</Link></li>
          <li><Link to="#about">About</Link></li>
          <li><Link to="#services">Services</Link></li>
          <li><Link to="#contact">Contact</Link></li>
        </ul>
      </nav>
      <UserDelete userId = {userid}/>
    </div>
  );
};

export default HumbugerMenu;
