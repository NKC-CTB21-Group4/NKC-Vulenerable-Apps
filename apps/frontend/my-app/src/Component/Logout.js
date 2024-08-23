import React, { useState, useEffect, useContext } from 'react';
import './css/Logout.css';
import AuthContext from '../Utils/AuthProvider';

const Logout = () => {
  const [message, setMessage] = useState('');
  const { logout } = useContext(AuthContext);

  useEffect(() => {
    const logoutModalBtn = document.querySelector(".logout-modalBtn");
    const logoutDialog = document.querySelector("#logout-dialog");

    const closeDialog = () => {
      logoutDialog.close();
      setMessage('');
    };

    if (logoutModalBtn) {
      logoutModalBtn.addEventListener("click", closeDialog);
    }

    return () => {
      if (logoutModalBtn) {
        logoutModalBtn.removeEventListener("click", closeDialog);
      }
    };
  }, []);

  const handleLogout = async () => {
    try {
      await logout();
      setMessage('Logout successful');
      document.querySelector("#logout-dialog").close();
      window.location.href = '/';
    } catch (error) {
      console.error('Logout failed:', error);
      localStorage.removeItem('authToken');
    }
  };

  return (
    <dialog id="logout-dialog">
      <button className="logout-modalBtn">×</button>
      <h2 className="logout-text">ログアウト</h2>
      <p className="logout-confirm-text">本当にログアウトしますか？</p>
      <button className="logout-button" onClick={handleLogout}>ログアウト</button>
      {message && <p className="message">{message}</p>}
    </dialog>
  );
};

export default Logout;
