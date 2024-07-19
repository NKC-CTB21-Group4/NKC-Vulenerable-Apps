import React, { useContext, useState } from 'react';
import './Contentinfo.css'; // CSSファイルをインポート
import Userinfo from './Userinfo';
import Content from './Content';
import Fav from './Fav';
import deletePost from './DeletePost';
import AuthContext from '../Utils/AuthProvider';
import ReportPost from './ReportPost';

function Contentinfo({ src, alt, username, userid, postid, content, imagepath, handleDelete }) {
  const { user } = useContext(AuthContext);
  const currentUserId = user?.id;
  const [isReportOpen, setIsReportOpen] = useState(false);

  const handleDeleteClick = async () => {
    try {
      await deletePost(postid, userid);
      handleDelete(postid);
      alert('ポストが削除されました');
    } catch (error) {
      alert('ポストの削除に失敗しました');
    }
  };

  const handleReportClick = () => {
    setIsReportOpen(true);
  };

  const handleReportClose = () => {
    setIsReportOpen(false);
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
        <button className="post-report-button" onClick={handleReportClick}>
          通報
        </button>
        {isReportOpen && <ReportPost postid={postid} onClose={handleReportClose} />}
        {currentUserId === userid && (
          <button className="post-delete-button" onClick={handleDeleteClick}>
            削除
          </button>
        )}
      </div>
    </div>
  );
}

export default Contentinfo;
