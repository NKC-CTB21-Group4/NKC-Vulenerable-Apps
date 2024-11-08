import React from 'react';
import './css/Profileinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Profile from './Profile';



function Profileinfo({src, alt, username, userid, profile}) {
  

  return (
    <div className="Profileinfo-container">
        <Userinfo src={src} alt={alt} userid={userid} username={username}/>
        <Profile profile={profile}/>
    </div>
  );
}

export default Profileinfo;
