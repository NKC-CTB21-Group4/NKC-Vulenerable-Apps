// login.js
import React, { useState,useContext } from 'react';
import './Login.css'
import AuthContext from '../Utils/AuthProvider';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');
  const { login } = useContext(AuthContext);

  const handleLogin = async (e) => {
    e.preventDefault();

    // ログイン関数を呼び出す
    try {
      await login(email, password);
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
