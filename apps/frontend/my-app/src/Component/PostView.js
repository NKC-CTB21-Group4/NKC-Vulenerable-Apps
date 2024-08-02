import React, { useState } from 'react';
import './css/PostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';
import {FetchPosts} from '../api/post';

function PostView({ searchKeyword }) {
  const [posts, setPosts] = useState([]);
  const { data, error, mutate } = FetchPosts('http://localhost:8080/posts');

  // データが取得できたときに、postsステートを更新
  if (data && posts.length === 0) {
    const postArray = Object.values(data.data).reverse();
    setPosts(postArray);
  }

  if (error) return <div>Failed to load</div>;
  if (!data) return <div>Loading...</div>;

  const handleNewPost = (event) => {
    setPosts((prevPosts) => [event.detail, ...prevPosts]);
  };

  window.addEventListener('newPost', handleNewPost);

  const handleDelete = (postid) => {
    // 状態を手動で更新
    setPosts(posts.filter((post) => post.id !== postid));
    // mutate関数でサーバーから最新のデータを取得
    mutate();
  };

  const filteredPosts = posts.filter(post => 
    post.content.toLowerCase().includes(searchKeyword.toLowerCase()) ||
    post.author_name.toLowerCase().includes(searchKeyword.toLowerCase())
  );

  return (
    <div className="postview-container">
      {filteredPosts.map((post) => (
        <Contentinfo
          key={post.id}
          src={`http://localhost:8080/users/${post.author_id}/avatar`} 
          alt=""
          username={post.author_name}
          userid={post.author_id}
          content={post.content}
          postid={post.id}
          imagepath={post.image_path}
          handleDelete={handleDelete}
        />
      ))}
    </div>
  );
}

export default PostView;