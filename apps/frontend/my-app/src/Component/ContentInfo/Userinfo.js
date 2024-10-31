import React from 'react';
import './css/Userinfo.css'; // CSSファイルをインポート
import Username from './Username'; // Usernameコンポーネントのインポート
import Userid from './Userid'; // Useridコンポーネントのインポート
import Icon from './Icon'; // Iconコンポーネントのインポート



function Userinfo({src, alt, username, userid, profile}) {
  

  return (
   
    <div className="userinfo-container">
      <Icon src={src} alt={alt} />
      <div className="userinfo-text">
        <Username username={username} />
        <Userid userid={userid} />
      </div>
    </div>

  );
}

export default Userinfo;
