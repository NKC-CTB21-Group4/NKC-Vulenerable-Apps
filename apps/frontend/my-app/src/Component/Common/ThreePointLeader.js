import React, { useState, useContext, useEffect } from 'react';
import AuthContext from '../../Utils/AuthProvider';
import './css/ThreePointLeader.css';
import ThreePointLeaderMenu from './ThreePointLeaderMenu';
import UserDelete from '../Users/UserDelete';
import tegakileader from '../../images/tegakileader.png';
import UserChange from '../Users/UserChange';

const ThreePointLeader = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { user } = useContext(AuthContext);
  const userid = user.id;

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  const closeMenu = () => {
    setIsOpen(false);
  };

  // メニューの外側クリックを検出するためのイベントリスナーを追加
  useEffect(() => {
    const handleClickOutside = (event) => {
      const leaderBtn = document.getElementById('leader-btn');
      const navMenu = document.querySelector('.nav-menu');

      if (isOpen && navMenu && !navMenu.contains(event.target) && leaderBtn !== event.target) {
        closeMenu();
      }
    };

    document.addEventListener('click', handleClickOutside);

    return () => {
      document.removeEventListener('click', handleClickOutside);
    };
  }, [isOpen]);

  return (
    <div>
      <img src={tegakileader} id="leader-btn" className={`three-point-leader ${isOpen ? 'open' : ''}`} onClick={toggleMenu} />
      <ThreePointLeaderMenu isOpen={isOpen} onClose={closeMenu} />
      <UserDelete userId={userid} />
      <UserChange userId={userid} />
    </div>
  );
};

export default ThreePointLeader;