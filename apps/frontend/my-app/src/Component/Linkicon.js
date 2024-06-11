// Linkicon.js
import React from 'react';
import { Link } from 'react-router-dom';
import './Linkicon.css'; // CSSファイルをインポート

function Linkicon({ src, alt = "Link icon", to }) {
  return (
    <div className="Linkicon-container">
      <Link to={to}>
        <img src={src} alt={alt} className="Link-icon" />
      </Link>
    </div>
  );
}

export default Linkicon;
