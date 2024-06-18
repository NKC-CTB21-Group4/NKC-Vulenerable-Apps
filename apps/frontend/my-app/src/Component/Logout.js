// Logout.js

import React, { useState } from 'react';
import './Logout.css';

const Logout = () => {
  const [message, setMessage] = useState('');

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
      } else {
        setMessage('Logout failed');
      }
    } catch (error) {
      console.error('Logout failed:', error);
      setMessage('Logout failed');
    }
  };

  return (
    <div className="logout-container">
      <h2>ログアウト</h2>
      <button className="logout-button" onClick={handleLogout}>ログアウト</button>
      {message && <p className="message">{message}</p>}
    </div>
  );
};

export default Logout;
