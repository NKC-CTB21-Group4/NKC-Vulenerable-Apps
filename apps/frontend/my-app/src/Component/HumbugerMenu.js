// HumbugerMenu.js
import React, { useState, useEffect,useContext } from 'react';
import './HumbugerMenu.css';
import AuthContext from '../Utils/AuthProvider';

const HumbugerMenu = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [authSuccess, setAuthSuccess] = useState(false);
  const [authError, setAuthError] = useState('');
  const { user } =useContext(AuthContext);
  const userId = user.id; 

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  useEffect(() => {
    const userDeleteBtn = document.querySelector("#user-delete-btn");
    const userDeleteModalBtn = document.querySelector("#user-delete-modalBtn");
    const userDeleteDialog = document.querySelector("#user-delete-dialog");

    const openDialog = () => {
      userDeleteDialog.showModal();
    };

    const closeDialog = () => {
      userDeleteDialog.close();
      setEmail('');
      setPassword('');
      setAuthSuccess(false);
      setAuthError('');
    };

    if (userDeleteBtn && userDeleteModalBtn) {
      userDeleteBtn.addEventListener("click", openDialog);
      userDeleteModalBtn.addEventListener("click", closeDialog);
    }

    // Cleanup event listeners on component unmount
    return () => {
      if (userDeleteBtn && userDeleteModalBtn) {
        userDeleteBtn.removeEventListener("click", openDialog);
        userDeleteModalBtn.removeEventListener("click", closeDialog);
      }
    };
  }, []);

  const handleAuthenticate = async () => {
    try {
      const response = await fetch('http://localhost:8080/auth/token', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email,
          password,
        }),
      });

      if (!response.ok) {
        throw new Error('Authentication failed');
      }

      const responseData = await response.json();
      console.log('Authentication successful:', responseData);
      localStorage.setItem('authToken', responseData.data);
      setAuthSuccess(true);
      setAuthError('');
    } catch (error) {
      console.error('Error during authentication:', error.message);
      setAuthSuccess(false);
      setAuthError('Authentication failed. Please try again.');
    }
  };

  const handleDelete = async () => {
    try {
      const response = await fetch(`http://localhost:8080/users/${userId}`, {
        method: 'DELETE',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
      });

      if (!response.ok) {
        throw new Error('Failed to delete user');
      }

      console.log('User deleted successfully');
      document.querySelector("#user-delete-dialog").close();
    } catch (error) {
      console.error('Error deleting user:', error.message);
      // Handle error state or display error message to user
    }
  };

  return (
    <div>
      <div className={`hamburger-menu ${isOpen ? 'open' : ''}`} onClick={toggleMenu}>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <nav className={`nav-menu ${isOpen ? 'open' : ''}`}>
        <ul>
          <li><a href="#user-delete" id="user-delete-btn">User削除</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>

      <dialog id="user-delete-dialog">
        <h2>ユーザー削除</h2>
        <p>本当に削除しますか？</p>
        <form>
          <div className={authSuccess ? 'input-success' : ''}>
            <label htmlFor="email">Email:</label>
            <input
              type="email"
              id="email"
              name="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <div className={authSuccess ? 'input-success' : ''}>
            <label htmlFor="password">Password:</label>
            <input
              type="password"
              id="password"
              name="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          {authError && <p className="error">{authError}</p>}
          {!authSuccess && (
            <button type="button" onClick={handleAuthenticate}>
              認証
            </button>
          )}
        </form>
        {authSuccess && (
          <button id="confirm-delete-btn" onClick={handleDelete}>
            削除
          </button>
        )}
        <button id="user-delete-modalBtn">×</button>
      </dialog>
    </div>
  );
};

export default HumbugerMenu;
