import React from 'react';
import '../css/UserFollow.css';

const UserFollow = ({  onUserFollowClick,isFollowed }) => {
  return (
    <input
      type="button"
      className="User-Follow-button"
      onClick={ onUserFollowClick }
      value={isFollowed ? "フォロー解除" : "フォロー"}
    >
   
    </input>
  );
};

export default UserFollow;
