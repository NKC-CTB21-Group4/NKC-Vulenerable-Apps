import React, { useContext, useEffect, useState } from 'react';
import './MyPostView.css'; // CSSファイルをインポート
import Contentinfo from './Contentinfo';
import AuthContext from '../Utils/AuthProvider';

function MyPostView() { // デフォルト値として空の配列を設定
  const [posts, setPosts] = useState([]); 
  const { user } = useContext(AuthContext);
  const userid = user?.id;

  useEffect(() => {
    const fetchPosts = async () => {
      if(!userid)return;
      try {
        const response = await fetch(`http://localhost:8080/users/${userid}/posts`);
        const json = await response.json();
        setPosts(json.data);
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    };

    fetchPosts();
  }, [userid]);

  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  return (
    <div className="mypostview-container">
      {posts.map((post) => (
        <Contentinfo
          key={post.id}
          src={`http://localhost:8080/users/${userid}/avatar`}// srcとaltはUserinfoコンポーネントが使っている場合に設定
          alt=""
          username={post.author_name}
          userid={post.author_id}
          content={post.content}
          postid={post.id}
          imagepath={post.image_path}
          onDelete={handleDelete} // handleDelete関数のプロップス名を修正
        />
      ))}
    </div>
  );
}

export default MyPostView;
