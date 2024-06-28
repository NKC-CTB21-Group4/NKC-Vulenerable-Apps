import React, { useContext, useEffect, useState } from 'react';
import './PostView.css'; // CSSファイルをインポート
import Contentinfo from './Contentinfo';
import Iconhavertz from '../images/havertz.png';

function PostView() { // デフォルト値として空の配列を設定
  const [posts, setPosts] = useState([]); 

  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const response = await fetch(`http://localhost:8080/posts`);
        const json = await response.json();
        const postarray = Object.values(json.data);
        setPosts(postarray);
        console.log(postarray);
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    };

    fetchPosts();
  }, []);

  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  return (
    <div className="postview-container">
      {posts.map((post) => (
        <Contentinfo
          key={post.id}
          src={`http://localhost:8080/users/${post.author_id}/avatar`} // srcとaltはUserinfoコンポーネントが使っている場合に設定
          alt=""
          username={post.author_name}
          userid={post.author_id}
          content={post.content}
          postid={post.id}
          handleDelete={handleDelete}
        />
      ))}
    </div>
  );
}

export default PostView;
