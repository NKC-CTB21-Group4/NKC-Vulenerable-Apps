// Login.js

import React, { useState } from 'react';
import './Login.css';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();

    const loginData = {
      email,
      password
    };

    const apiEndpoint = 'http://localhost:8080/auth/token';

    try {
      const response = await fetch(apiEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(loginData)
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      const json = await response.json();
      const receivedToken = json.data;

      // 認証トークンをlocalStorageに保存
      localStorage.setItem('authToken', receivedToken);

      setMessage('Login successful');
    } catch (error) {
      setMessage('Login failed: ' + error.message);
    }
  };

  return (
    <div className="login-container">
      <h2 className="login-title">ログイン</h2>
      <form onSubmit={handleLogin} className="login-form">
        <div className="login-form-group">
          <label className="login-label">Eメール:</label>
          <input
            type="email"
            className="login-input"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div className="login-form-group">
          <label className="login-label">パスワード:</label>
          <input
            type="password"
            className="login-input"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        <button type="submit" className="login-button">ログイン</button>
      </form>
      {message && <p className="login-message">{message}</p>}
    </div>
  );
};

export default Login;
