import React from 'react';
import './css/PullDownUserList.css';

const PullDownUserList = ({ users, onUserClick }) => {
  return (
    <ul className="pull-down-user-list">
      {users.map(user => (
        <li key={user.id} onClick={() => onUserClick(user)}>
          {user.username}
        </li>
      ))}
    </ul>
  );
};

export default PullDownUserList;
