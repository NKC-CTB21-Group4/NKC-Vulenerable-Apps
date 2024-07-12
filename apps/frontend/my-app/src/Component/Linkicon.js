import React from 'react';
import { Link } from 'react-router-dom';
import './Linkicon.css'; // CSSファイルをインポート

function Linkicon({ src, alt, to, text, onClick }) {
  return (
    <div className="Linkicon-container">
      <Link to={to} onClick={onClick}>
        <img src={src} alt={alt} className="Link-icon" />
      </Link>
      <div className="linktext">{text}</div>
    </div>
  );
}

export default Linkicon;
