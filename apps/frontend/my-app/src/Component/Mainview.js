import React from 'react';
import './Mainview.css'; // CSSファイルをインポート
import Linkview from './Linkview';
import Postview from './Postview';
import Header from './Header';

function Mainview({ links, posts }) {

  return (
    <div className="mainview-container">
      <Linkview links={links} />
    <div className="header-posts-container">
      <Header/>
      <Postview posts={posts} /> {/* posts 全体を渡す */}
    </div>
    </div>
  );
}

export default Mainview;
