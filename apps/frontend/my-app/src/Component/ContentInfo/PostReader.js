import React, { useState, useEffect, useContext } from 'react';
import './css/PostReader.css';
import PostReaderMenu from './PostReaderMenu';
import tegakireader from '../../images/tegakireader.png';
import ReportPost from '../ReportPost';
import AuthContext from '../../Utils/AuthProvider';
import deletePost from '../Posts/DeletePost';

const PostReader = ({postid, userid}) => {
  const [isOpen, setIsOpen] = useState(false);
  const [isReporting, setIsReporting] = useState(false);
  const { user } = useContext(AuthContext);
  const currentUserId = user?.id;

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  const closeMenu = () => {
    setIsOpen(false);
  };

  const handleReport = () => {
    setIsReporting(true);
    closeMenu();
  };

  const closeReport = () => {
    setIsReporting(false);
  };

  const handleDelete = async () => {
    const confirmDelete = window.confirm("本当にこのポストを削除しますか？");
    if (!confirmDelete) {
      return;
    }

    try {
      await deletePost(postid, currentUserId);
      alert('ポストが削除されました');
      // ここで削除後の処理を追加できます
    } catch (error) {
      alert('ポストの削除に失敗しました');
    }
  };

  useEffect(() => {
    const handleClickOutside = (event) => {
      const leaderBtn = document.getElementById(`post-leader-btn${postid}`);
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
    <div className="post-reader-container">
      <img 
        src={tegakireader} 
        id={`post-leader-btn${postid}`} 
        className={`post-leader ${isOpen ? 'open' : ''}`} 
        onClick={toggleMenu} 
        alt="Post Menu" 
      />
      {isOpen && (
        <div className="menu-container">
          <PostReaderMenu 
            isOpen={isOpen} 
            onClose={closeMenu} 
            canDelete={currentUserId === userid}
            handleDelete={handleDelete}
            handleReport={handleReport} 
          />
          <div className="overlay" onClick={closeMenu} />
        </div>
      )}
      {isReporting && <ReportPost postid={postid} onClose={closeReport} />}
    </div>
  );
};

export default PostReader;
