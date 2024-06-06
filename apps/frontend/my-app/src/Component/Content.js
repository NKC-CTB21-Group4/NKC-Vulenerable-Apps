import React from 'react';
import './Content.css'; // CSS ファイルをインポート

function Content({ content }) {
  return (
    <div className="content-item">
      <p className="content-text">{content}</p>
    </div>
  );
}

export default Content;
