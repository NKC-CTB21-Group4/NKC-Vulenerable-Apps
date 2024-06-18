// createUser.js
import React, { useState } from 'react';
import './CreateUser.css';

const CreateUser = () => {
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');

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

      const json = await response.json();
      setMessage('User created successfully');
      console.log('User created:', json);
    } catch (error) {
      setMessage('Failed to create user: ' + error.message);
      console.error('Failed to create user:', error);
    }
  };

  return (
    <div className="create-user-container">
      <h2>ユーザー作成</h2>
      <form onSubmit={handleCreateUser} className="create-user-form">
        <div className="form-group">
          <label>ユーザーネーム:</label>
          <input
            type="text"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label>Eメール:</label>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label>パスワード:</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        <button type="submit">ユーザー作成</button>
      </form>
      {message && <p className="message">{message}</p>}
    </div>
  );
};

export default CreateUser;
