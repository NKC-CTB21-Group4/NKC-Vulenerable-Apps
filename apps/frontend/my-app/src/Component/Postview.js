import React from 'react';
import './Postview.css'; // CSSファイルをインポート
import Contentinfo from './Contentinfo';

function Postview({ posts }) {
  return (
    <div className="postview-container">
      {posts.map((post, index) => (
        <Contentinfo
          key={index}
          src={post.src}
          alt={post.alt}
          username={post.username}
          userid={post.userid}
          content={post.content}
        />
      ))}
    </div>
  );
}

export default Postview;
