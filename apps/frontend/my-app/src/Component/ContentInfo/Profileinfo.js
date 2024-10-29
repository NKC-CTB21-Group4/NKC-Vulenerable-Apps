import React from 'react';
import './css/Profileinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Profile from './Profile';



function Profileinfo({src, alt, username, userid, profile}) {
  

  return (
    <div className="Profileinfo-container">
    <div className="userinfo-container">
      <Userinfo src={src} alt={alt} userid={userid} username={username}/>
    </div>
    <div className="profileinfo-profilecontainer">
    <Profile profile={profile}/>
    </div>
    </div>
  );
}

export default Profileinfo;
