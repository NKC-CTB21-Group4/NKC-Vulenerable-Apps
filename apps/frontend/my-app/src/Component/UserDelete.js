// UserDelete.js
import React, { useState, useEffect } from 'react';
import './UserDelete.css';

const UserDelete = ({ userId }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [authSuccess, setAuthSuccess] = useState(false);
  const [authError, setAuthError] = useState('');

  useEffect(() => {
    const userDeleteModalBtn = document.querySelector("#user-delete-modalBtn");
    const userDeleteDialog = document.querySelector("#user-delete-dialog");

    const closeDialog = () => {
      userDeleteDialog.close();
      setEmail('');
      setPassword('');
      setAuthSuccess(false);
      setAuthError('');
    };

    if (userDeleteModalBtn) {
      userDeleteModalBtn.addEventListener("click", closeDialog);
    }

    return () => {
      if (userDeleteModalBtn) {
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
        throw new Error('認証に失敗しました');
      }

      const responseData = await response.json();
      console.log('認証成功:', responseData);
      setAuthSuccess(true);
      setAuthError('');
    } catch (error) {
      console.error('認証エラー:', error.message);
      setAuthSuccess(false);
      setAuthError('認証に失敗しました。もう一度試してください。');
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
        throw new Error('ユーザー削除に失敗しました');
      }

      console.log('ユーザー削除成功');
      document.querySelector("#user-delete-dialog").close();
    } catch (error) {
      console.error('削除エラー:', error.message);
    }
  };

  return (
    <dialog id="user-delete-dialog">
      <h2>ユーザー削除</h2>
      <button id="user-delete-modalBtn">キャンセル</button>
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
    </dialog>
  );
};

export default UserDelete;
