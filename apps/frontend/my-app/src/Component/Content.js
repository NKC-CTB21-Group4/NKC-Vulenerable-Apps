import React from 'react';
import './Content.css'; // CSS ファイルをインポート

function Content({ content,imagepath }) {
  console.log(imagepath);
  return (
    <div className="content-item">
      <p className="content-text">{content}</p>
      {imagepath && <img className="post-image" src={`http://localhost:8080${imagepath}`} alt=""/>}
    </div>
  );
}

export default Content;
