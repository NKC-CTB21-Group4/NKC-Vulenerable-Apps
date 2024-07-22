import React, { useContext, useState } from 'react';
import './Contentinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Content from './Content';
import Fav from './Fav';

import PostReader from './PostReader';

function Contentinfo({ src, alt, username, userid, postid, content, imagepath, handleDelete,postOwnerId }) {


  return (
    <div className="contentinfo-container">
      <div className="userinfo-content">
        <Userinfo src={src} alt={alt} username={username} userid={userid} />
        <div className="content">
          <Content content={content} imagepath={imagepath}/>
        </div>
      </div>
      <div className="post-actions">
        <div className="Fav">
          <Fav postid={postid} />
        </div>
        <PostReader userid={userid} postid={postid} handleDelete={handleDelete} postOwnerId={postOwnerId} />
      </div>
    </div>
  );
}

export default Contentinfo;
