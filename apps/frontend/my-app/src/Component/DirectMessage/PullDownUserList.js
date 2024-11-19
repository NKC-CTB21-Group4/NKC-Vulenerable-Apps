import React from 'react';
import './css/PullDownUserList.css';
import defaultAvatar from '../../images/tegakicreateuser.png'

const PullDownUserList = ({ users, onUserClick }) => {
  return (
    <ul className="pull-down-user-list">
      {users.map(user => (
        <li key={user.id} onClick={() => onUserClick(user)}>
        <img src={user.avatar_path ? `http://localhost:8080${user.avatar_path}` : defaultAvatar } alt={`${user.username}'s avatar`} />
        {user.username}
        </li>
      ))}
    </ul>
  );
};

export default PullDownUserList;
