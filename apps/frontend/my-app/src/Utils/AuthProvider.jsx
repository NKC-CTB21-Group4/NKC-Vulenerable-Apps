// AuthContext.js
import React, { createContext, useState,useEffect } from 'react';

const AuthContext = createContext();

export function AuthProvider({ children }) {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [user, setUser] = useState({});
  const [isAdmin,setIsAdmin] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('authToken');
    if (token) {
      const parts = token.split('.');
      const decodedPayload = atob(parts[1]); // Base64デコード 
      const payload = JSON.parse(decodedPayload); // JSONパース
      setUser(payload.user);
      if(payload.user["is_admin"] === true)setIsAdmin(true);
      setIsAuthenticated(true);
    }
  }, []);

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
      const decodedPayload = atob(parts[1]); // Base64デコード 
      const payload = JSON.parse(decodedPayload); // JSONパース
      setUser(payload.user);
      if(payload.user["is_admin"] === true)setIsAdmin(true);
      // 認証トークンをlocalStorageに保存
      localStorage.setItem('authToken', receivedToken);
      setIsAuthenticated(true);
      console.log('Login successful');
      console.log(payload.user);
    } catch (error) {
      console.log('Login failed: ' + error.message);
    }
  };

  const logout = async () => {
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
        console.log('Logout successful');
        setIsAuthenticated(false);
        setIsAdmin(false);
        setUser({});
      } else {
        console.log('Logout failed');
      }

    } catch (error) {
      console.error('Logout failed:', error);
    }
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, user,isAdmin, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export default AuthContext;
