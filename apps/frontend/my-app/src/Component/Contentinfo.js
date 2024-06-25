import React from 'react';
import './Contentinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Content from './Content';
import Fav from './Fav';
import deletePost from './DeletePost';


function Contentinfo({src, alt, username, userid, postid, content,handleDelete}) {
  const handleClick = async() =>{
    try{
      await deletePost(postid,userid)
      handleDelete(postid)
      alert('ポストが削除されました');
    }
    catch(error){
      alert('ポストの削除に失敗しました');
    }
  }

  return (
    <div className="contentinfo-container">
      <div className="userinfo-content">
      <Userinfo src={src} alt={alt} username={username} userid={userid} />    
        <div className="content">
        <Content content={content}  />
        </div>
      </div>
      <div className='post-actions'>
      <Fav postid={postid}/>
      <button className="post-delete-button" onClick={handleClick}>
          削除
      </button>
      </div>
    </div>
  );
}

export default Contentinfo;
