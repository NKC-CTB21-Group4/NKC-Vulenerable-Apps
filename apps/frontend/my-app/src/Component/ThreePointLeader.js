// ThreePointLeader.js
import React, { useState, useContext } from 'react';
import AuthContext from '../Utils/AuthProvider'
import './ThreePointLeader.css';
import ThreePointLeaderMenu from './ThreePointLeaderMenu';
import UserDelete from './UserDelete';

const ThreePointLeader = () => {
  const [isOpen, setIsOpen] = useState(false);
  const {user} = useContext(AuthContext);
  const userid = user.id;

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  return (
    <div>
      <div className={`three-point-leader ${isOpen ? 'open' : ''}`} onClick={toggleMenu}>
        <div className="ellipsis horizontal"></div>
        <div className="ellipsis horizontal"></div>
        <div className="ellipsis horizontal"></div>
      </div>
      <ThreePointLeaderMenu isOpen={isOpen} />
      <UserDelete userId = {userid}/>
    </div>
  );
};

export default ThreePointLeader;
