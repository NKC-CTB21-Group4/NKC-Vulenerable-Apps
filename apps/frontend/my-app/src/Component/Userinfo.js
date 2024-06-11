import React from 'react';
import './Userinfo.css'; // CSSファイルをインポート




function Userinfo({src,alt,username,userid }) {
  
  return (
    <div className="userinfo-container">
    <img src={src} alt={alt} className="userinfo-icon" />
    <div className="userinfo-text">
    <div className="userinfoname">{username}</div>
    <div className="userinfoid">{userid}</div>
    </div>
    
    </div>
  );
}

export default Userinfo;
