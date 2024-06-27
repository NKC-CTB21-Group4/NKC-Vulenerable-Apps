import React from 'react';
import './MyPage.css'; // CSSファイルをインポート
import MyPostview from '../Component/MyPostView';
import Linkview from '../Component/Linkview';


function Mypage({ posts = [], userId, links }) {
  const myPosts = posts.filter(post => post.author_id === userId);
  return (
    <div className="mypage-container">
      <div className="linkview-container">
        <Linkview links={links}/>
      </div>
      <div className="postview-container">
        <MyPostview posts={myPosts} />
      </div>
    </div>
  );
}

export default Mypage;
