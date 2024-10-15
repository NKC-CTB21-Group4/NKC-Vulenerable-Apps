import React from 'react';
import './css/Userid.css'; // CSSファイルをインポート

function Userid({ userid }) {
  return (
    <div className="userid-container">
      <div className="userid">{userid}</div>
    </div>
  );
}

export default Userid;
