import React from 'react';
import '../css/PostReaderMenu.css';

const PostReaderMenu = ({ isOpen,canDelete,handleDelete, handleReport }) => {
  return (
    <nav className={`PRM-nav-menu ${isOpen ? 'open' : ''}`}>
      <ul>
      {canDelete && (
        <li><button className="PRM-bt"  onClick={handleDelete}>削除</button></li>
      )}
        <li><button className="PRM-bt" onClick={handleReport}>通報</button></li>
      </ul>
    </nav>
  );
};

export default PostReaderMenu;
