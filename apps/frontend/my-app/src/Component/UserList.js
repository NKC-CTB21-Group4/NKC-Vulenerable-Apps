import React, { useContext } from 'react';
import AuthContext from '../Utils/AuthProvider';
import {Link} from 'react-router-dom';

function UserList({ users, onUserSelect }) {
    const {user} = useContext(AuthContext);
    const userid = user.id;
  return (
    <div>
        <Link to="/Mainview" className="back-button"></Link>
    <div className="user-list">
      {users.map((user) => (
        <div key={user.id} onClick={() => onUserSelect(user)} className="user">
           <strong>ユーザー{userid === user.receiver_id ? user.sender_id : user.receiver_id}</strong><br></br>
           <strong>メッセージ{user.message}</strong>
        </div>
      ))}
    </div>
    </div>
  );
}

export default UserList;
