import React from 'react';
import './Userid.css'; // CSSファイルをインポート

function Userid({ userid }) {
  return (
    <div className="userid-container">
      <div className="userid">{userid}</div>
    </div>
  );
}

export default Userid;
