import React from 'react';
import './css/Profileinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Profile from './Profile';
import UserFollow from '../UserFollow';



function Profileinfo({src, alt, username, userid, profile,onUserIconClick,onUserFollowClick,isFollowed}) {
  return (
    <div className="Profileinfo-container">
        <Userinfo src={src} alt={alt} userid={userid} username={username} onUserIconClick={onUserIconClick}/>
        <Profile profile={profile}/>
        <UserFollow onUserFollowClick={()=> onUserFollowClick(userid)} isFollowed={isFollowed}/>
    </div>
  );
}

export default Profileinfo;

