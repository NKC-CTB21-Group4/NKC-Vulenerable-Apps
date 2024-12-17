import React, { useState, useEffect } from 'react';
import '../css/Logout.css';

const Logout = () => {
  const [message, setMessage] = useState('');

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
    const apiEndpoint = 'http://localhost:8080/auth/revoke-token';

    try {
      const response = await fetch(apiEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
        body: JSON.stringify({})
      });
      if (response.status === 200) {
        localStorage.removeItem('authToken');
        setMessage('Logout successful');
        document.querySelector("#logout-dialog").close();
        window.location.href = '/';
      } else {
        localStorage.removeItem('authToken');
        setMessage('Logout successful');
        document.querySelector("#logout-dialog").close();
        window.location.href = '/';
      }
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
