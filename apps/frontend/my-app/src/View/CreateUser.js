// CreateUser.js

import React, { useState ,useContext,useEffect} from 'react';
import AuthContext from '../Utils/AuthProvider';
import './CreateUser.css';
import {useNavigate} from 'react-router-dom';

const CreateUser = () => {
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');
  const { isAdmin,isAuthenticated} = useContext(AuthContext);
  const navigate = useNavigate();

  useEffect(() => {
    if (isAuthenticated) {
      navigate('/');
    }
  }, [isAuthenticated, navigate]);

  useEffect(() => {
    if (isAdmin) {
      navigate('/admin');
    }
  }, [isAdmin, navigate]);

  const handleCreateUser = async (e) => {
    e.preventDefault();

    const newUser = {
      username,
      email,
      password
    };

    const apiEndpoint = 'http://localhost:8080/users'; // ユーザー作成用のエンドポイントを指定

    try {
      const response = await fetch(apiEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(newUser)
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      setMessage('User created successfully');
      navigate('/');
    } catch (error) {
      setMessage('Failed to create user: ' + error.message);
      console.error('Failed to create user:', error);
    }
  };

  return (
    <div className="create-user-container">
      <h2>ユーザー作成</h2>
      <form onSubmit={handleCreateUser} className="create-user-form">
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

export default CreateUser;
