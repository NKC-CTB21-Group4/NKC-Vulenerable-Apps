import React from 'react';
import './Content.css'; // CSS ファイルをインポート

function Content({ contentData }) {
  return (
    <div className="content-wrapper">
      {contentData.length > 0 ? (
        contentData.map((item, index) => (
          <div key={index} className="content-item">
            <p className="content-text">{item.content}</p>
          </div>
        ))
      ) : (
        <div className="no-content">No content available</div>
      )}
    </div>
  );
}

export default Content;
