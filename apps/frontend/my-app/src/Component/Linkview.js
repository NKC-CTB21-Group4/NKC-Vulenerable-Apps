import React from 'react';
import './Linkview.css'; // CSSファイルをインポート
import Linkicon from './Linkicon';
import PostCreateButton from './PostCreateButton';
import ThreePointLeader from './ThreePointLeader';
function Linkview({ links }) {
  return (
    <div className="linkview-container">
      {links.map((link, index) => (
        <div className="linkicon-item">
          <Linkicon
            key={index}
            src={link.src}
            alt={link.alt}
            to={link.to}
            text={link.text}
          />
        </div>
      ))}
      <ThreePointLeader/>
      <PostCreateButton/>
    </div>
  );
}

export default Linkview;
