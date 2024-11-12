import React from 'react';
import './css/PullDownUserList.css';

const PullDownUserList = ({ users, onUserClick }) => {
  return (
    <ul className="pull-down-user-list">
      {users.map(user => (
        <li key={user.id} onClick={() => onUserClick(user)}>
        <img src={`http://localhost:8080${user.avatar_path}`} alt={`${user.username}'s avatar`} />
        {user.username}
        </li>
      ))}
    </ul>
  );
};

export default PullDownUserList;
