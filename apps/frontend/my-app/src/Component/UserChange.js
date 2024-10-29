import React, { useState, useEffect } from 'react';
import './css/UserChange.css'; 
const UserChange = ({ userId }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [newEmail, setNewEmail] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [authSuccess, setAuthSuccess] = useState(false);
  const [authError, setAuthError] = useState('');

  useEffect(() => {
    const userChangeModalBtn = document.querySelector("#user-change-modalBtn");
    const userChangeDialog = document.querySelector("#user-change-dialog");

    const closeDialog = () => {
      userChangeDialog.close();
      setEmail('');
      setPassword('');
      setNewEmail('');
      setNewPassword('');
      setAuthSuccess(false);
      setAuthError('');
    };

    if (userChangeModalBtn) {
      userChangeModalBtn.addEventListener("click", closeDialog);
    }

    return () => {
      if (userChangeModalBtn) {
        userChangeModalBtn.removeEventListener("click", closeDialog);
      }
    };
  }, []);

  // 認証処理
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

      setAuthSuccess(true);
      setAuthError('');
    } catch (error) {
      setAuthSuccess(false);
      setAuthError('認証に失敗しました。もう一度試してください。');
    }
  };

  // ユーザー情報更新処理
  const handleUpdate = async () => {
    try {
      const response = await fetch(`http://localhost:8080/users/${userId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
        body: JSON.stringify({
          email: newEmail || email, // 新しいメールがなければ現在のメールを使用
          password: newPassword || password // 新しいパスワードがなければ現在のパスワードを使用
        }),
      });

      if (!response.ok) {
        throw new Error('ユーザー情報の更新に失敗しました');
      }

      document.querySelector("#user-change-dialog").close();
      window.location.href = '/Mypage'; // 更新後にリダイレクト
    } catch (error) {
      console.error('更新エラー:', error.message);
    }
  };

  return (
    <dialog id="user-change-dialog">
      <h2>E-mail,Password変更</h2>
      <button id="user-change-modalBtn">×</button>
      <form>
        <div className={authSuccess ? 'input-success' : ''}>
          <label htmlFor="email">現在のEmail:</label>
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
          <label htmlFor="password">現在のPassword:</label>
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
        <form>
          <div>
            <label htmlFor="newEmail">新しいEmail:</label>
            <input
              type="email"
              id="newEmail"
              name="newEmail"
              value={newEmail}
              onChange={(e) => setNewEmail(e.target.value)}
            />
          </div>
          <div>
            <label htmlFor="newPassword">新しいPassword:</label>
            <input
              type="password"
              id="newPassword"
              name="newPassword"
              value={newPassword}
              onChange={(e) => setNewPassword(e.target.value)}
            />
          </div>
          <button type="button" onClick={handleUpdate}>
            更新
          </button>
        </form>
      )}
    </dialog>
  );
};

export default UserChange;