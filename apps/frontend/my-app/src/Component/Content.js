import React from 'react';

function Content({ contentData}) {
  return (
    <div>
      {contentData.length > 0 ? (
        contentData.map((item) => (
          <div>
            <p>{item.content}</p>
          </div>
        ))
      ) : (
        <div>No content available</div>
      )}
    </div>
  );
}

export default Content;