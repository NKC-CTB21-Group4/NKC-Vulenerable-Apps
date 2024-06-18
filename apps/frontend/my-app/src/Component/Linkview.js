import React from 'react';
import './Linkview.css'; // CSSファイルをインポート
import Linkicon from './Linkicon';

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
      </div>
    );
  }

export default Linkview;
