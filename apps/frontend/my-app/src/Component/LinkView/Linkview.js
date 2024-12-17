import React from 'react';
import './css/Linkview.css'; // CSSファイルをインポート
import Linkicon from './Linkicon';
import PostCreateButton from '../PostCreateButton';
import ThreePointLeader from '../Common/ThreePointLeader';

function Linkview({ links,onLinkClick }) {
  return (
    <div className="linkview-container">
      {links.map((link, index) => (
        <div key={index} className="linkicon-item">
          <Linkicon
            src={link.src}
            alt={link.alt}
            to={link.to}
            text={link.text}
            onClick={() => {
              if (link.onClick) {
                link.onClick();
              }
              if (onLinkClick) {
                onLinkClick();
              }
            }}
          />
        </div>
      ))}
      <ThreePointLeader/>
      <PostCreateButton/>
    </div>
  );
}

export default Linkview;
