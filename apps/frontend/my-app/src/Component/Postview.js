import React from 'react';
import './Postview.css'; // CSSファイルをインポート
import Contentinfo from './Contentinfo';

function Postview({ posts = [] }) { // デフォルト値として空の配列を設定
  return (
    <div className="postview-container">
      {posts.map((post) => (
        <Contentinfo
          key={post.id}
          src="" // srcとaltはUserinfoコンポーネントが使っている場合に設定
          alt=""
          username={post.author_name}
          userid={post.author_id}
          content={post.content}
          postid={post.id}
        />
      ))}
    </div>
  );
}

export default Postview;
