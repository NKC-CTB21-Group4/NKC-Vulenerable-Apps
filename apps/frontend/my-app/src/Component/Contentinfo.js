import React from 'react';
import './Contentinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Content from './Content';
import Fav from './Fav';


function Contentinfo({src, alt, username, userid, postid, content}) {
  

  return (
    <div className="contentinfo-container">
      <div className="userinfo-content">
      <Userinfo src={src} alt={alt} username={username} userid={userid} />    
    <div className="content">
    <Content content={content}  />
    </div>
    </div>
      <Fav postid={postid}/>
    </div>
  );
}

export default Contentinfo;
