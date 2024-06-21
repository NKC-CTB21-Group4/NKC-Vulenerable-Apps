import React from 'react';
import './Mainview.css'; // CSSファイルをインポート
import Linkview from './Linkview';
import Postview from './Postview';

function Mainview({ links, posts }) {
  console.log("Links: ", links);
  console.log("Posts: ", posts);

  return (
    <div className="mainview-container">
      <Linkview links={links} />
      <Postview posts={posts} /> {/* posts 全体を渡す */}
    </div>
  );
}

export default Mainview;
