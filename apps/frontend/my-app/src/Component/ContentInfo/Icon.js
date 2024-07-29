import React from 'react';
import './css/Icon.css'; // CSSファイルをインポート

function Icon({ src, alt = "User Icon" }) {
  return (
    <div className="icon-container">
      <img src={src} alt={alt} className="user-icon" />
    </div>
  );
}

export default Icon;
