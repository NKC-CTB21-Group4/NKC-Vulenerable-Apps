import React from 'react';
import './css/Username.css'; // CSSファイルをインポート

function Username({ username }) {
  return (
    <div className="username-container">
      <div className="username">{username}</div>
    </div>
  );
}

export default Username;
