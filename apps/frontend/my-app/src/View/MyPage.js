import React from 'react';
import './MyPage.css'; // CSSファイルをインポート
import Postview from '../Component/Postview';


function Mypage({ posts = [], userId }) {
  const myPosts = posts.filter(post => post.author_id === userId);
  return (
    <div className="mypage-container">
      <div className="postview-container">
        <Postview posts={myPosts} />
      </div>
    </div>
  );
}

export default Mypage;
