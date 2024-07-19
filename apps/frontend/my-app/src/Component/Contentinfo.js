import React, { useContext } from 'react';
import './Contentinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Content from './Content';
import Fav from './Fav';
import deletePost from './DeletePost';
import AuthContext from '../Utils/AuthProvider';

function Contentinfo({ src, alt, username, userid, postid, content, imagepath, handleDelete }) {
  const { user } = useContext(AuthContext);
  const currentUserId = user?.id;

  const handleClick = async () => {
    const confirmDelete = window.confirm("本当にこのポストを削除しますか？");
    if (!confirmDelete) {
      return; // キャンセルされた場合は何もしない
    }


    try {
      await deletePost(postid, userid);
      handleDelete(postid);
      alert('ポストが削除されました');
    } catch (error) {
      alert('ポストの削除に失敗しました');
    } 
  };

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
        {currentUserId === userid && (
          <button className="post-delete-button" onClick={handleClick}>
            削除
          </button>
        )}
      </div>
    </div>
  );
}

export default Contentinfo;
