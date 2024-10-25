import React, { createContext, useState, useEffect } from 'react';

const AuthContext = createContext();

export function AuthProvider({ children }) {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [user, setUser] = useState({});
  const [isAdmin, setIsAdmin] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('authToken');
    if (token) {
      const parts = token.split('.');
      const decodedPayload = atob(parts[1]); // Base64デコード
      const payload = JSON.parse(decodedPayload); // JSONパース
      setUser(payload.user);
      if (payload.user["is_admin"] === true) setIsAdmin(true);
      setIsAuthenticated(true);
    }
  }, []);

  // ログイン処理
  const login = async (email, password) => {
    const apiEndpoint = 'http://localhost:8080/auth/token';
    try {
      const response = await fetch(apiEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      const json = await response.json();
      const receivedToken = json.data;
      const parts = receivedToken.split('.');
      const decodedPayload = atob(parts[1].replace(/-/g, '+').replace(/_/g, '/')); // Base64デコード
      const payload = JSON.parse(decodedPayload); // JSONパース
      setUser(payload.user);
      if (payload.user["is_admin"] === true) setIsAdmin(true);
      // 認証トークンをlocalStorageに保存 
      localStorage.setItem('authToken', receivedToken);
      setIsAuthenticated(true);
    } catch (error) {
      console.error('Login failed: ' + error.message);
      throw new Error('Network response was not ok');
    }
  };

  // ログアウト処理
  const logout = async () => {
    const apiEndpoint = 'http://localhost:8080/auth/revoke-token';

    try {
      const response = await fetch(apiEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
        },
        body: JSON.stringify({}),
      });
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      if (response.status === 200) {
        localStorage.removeItem('authToken');
        setIsAuthenticated(false);
        setIsAdmin(false);
        setUser({});
      }
    } catch (error) {
      console.error('Logout failed:', error);
      throw new Error('Network response was not ok');
    }
  };

  // ユーザー情報を更新し、localStorageに保存する関数
  const updateUser = (updatedUser) => {
    setUser(updatedUser);
  };

  const updateToken = (token) => {
    localStorage.setItem('authToken', token);
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, user, isAdmin, login, logout, updateUser ,updateToken}}>
      {children}
    </AuthContext.Provider>
  );
}

export default AuthContext;
