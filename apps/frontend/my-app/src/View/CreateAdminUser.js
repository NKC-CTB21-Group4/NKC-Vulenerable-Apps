// CreateUser.js

import React, { useState } from 'react';
import './css/CreateUser.css';
import {useNavigate } from 'react-router-dom';
import { createAdminUser } from '../Component/api/user';

const CreateAdminUser = () => {
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');
  const navigate = useNavigate();

  const handleCreateAdminUser = async (e) => {
    e.preventDefault();

    const newUser = {
      username,
      email,
      password
    };

    const url = 'http://localhost:8080/admin/users'; // ユーザー作成用のエンドポイントを指定

    try {
      await createAdminUser(url,newUser);
      setMessage('Admin User created successfully');
      navigate('/admin');      
    } catch (error) {
      setMessage('Failed to create admin user: ' + error.message);
      console.error('Failed to create admin user:', error);
    }
  };

  return (
    <div className="create-user-container">
      <h2>管理ユーザー作成</h2>
      <form onSubmit={handleCreateAdminUser} className="create-user-form">
        <div className="create-user-form-group">
          <label className='create-user-label'>ユーザーネーム:</label>
          <input
            type="text"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            className="create-user-input"
            required
          />
        </div>
        <div className="create-user-form-group">
          <label className='create-user-label'>Eメール:</label>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="create-user-input"
            required
          />
        </div>
        <div className="create-user-form-group">
          <label className='create-user-label'>パスワード:</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="create-user-input"
            required
          />
        </div>
        <button type="submit" className="create-user-button">ユーザー作成</button>
      </form>
      {message && <p className="create-user-message">{message}</p>}
    </div>
  );
};

export default CreateAdminUser;
