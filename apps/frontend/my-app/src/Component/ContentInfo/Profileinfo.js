import React from 'react';
import './css/Profileinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Profile from './Profile';
import UserFollow from '../UserFollow';
import UserFollowList from '../UserFollowList';
import UserFollowerList from '../UserFollowerList';



function Profileinfo({src, alt, username, userid, profile,onUserIconClick,onUserFollowClick,isFollowed,users,followerusers,onFollowListClick,onFollowerListClick}) {
  return (
    <div className="Profileinfo-container">
        <Userinfo src={src} alt={alt} userid={userid} username={username} onUserIconClick={onUserIconClick}/>
        <Profile profile={profile}/>
        <UserFollow onUserFollowClick={()=> onUserFollowClick(userid)} isFollowed={isFollowed}/>
    <div className="list-container">
        <UserFollowList users={users} onFollowListClick={onFollowListClick} />
        <UserFollowerList followerusers={followerusers} onFollowerListClick={onFollowerListClick} />
    </div>   
        
        
    </div>
  );
}

export default Profileinfo;

