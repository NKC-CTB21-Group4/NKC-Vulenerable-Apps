import React from 'react';
import './MyPage.css'; // CSSファイルをインポート
import Postview from '../Component/Postview';
import Linkview from '../Component/Linkview';


function Mypage({ posts = [], userId, links }) {
  const myPosts = posts.filter(post => post.author_id === userId);
  console.log(myPosts);
  return (
    <div className="mypage-container">
      <div className="linkview-container">
        <Linkview links={links} />
      </div>
      <div className="postview-container">
        <Postview posts={myPosts} />
      </div>
    </div>
  );
}

export default Mypage;
