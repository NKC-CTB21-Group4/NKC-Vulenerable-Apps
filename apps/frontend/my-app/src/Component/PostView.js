import React, { useEffect, useState } from 'react';
import './PostView.css'; // CSSファイルをインポート
import Contentinfo from './Contentinfo';

function PostView({ searchKeyword }) {
  const [posts, setPosts] = useState([]);

  const fetchPosts = async () => {
    try {
      const response = await fetch(`http://localhost:8080/posts`);
      const json = await response.json();
      const postarray = Object.values(json.data).reverse(); // 逆順にソート
      setPosts(postarray);
    } catch (error) {
      console.error('Error fetching posts:', error);
    }
  };

  useEffect(() => {
    fetchPosts();

    const handleNewPost = (event) => {
      fetchPosts(); // 新しい投稿が追加されたら再度データを取得
    };

    window.addEventListener('newPost', handleNewPost); // カスタムイベントnewPostが発生したときにhandleNewPostが呼び出される

    return () => {
      window.removeEventListener('newPost', handleNewPost);
    };
  }, []);

  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
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
          src={`http://localhost:8080/users/${post.author_id}/avatar`} // srcとaltはUserinfoコンポーネントが使っている場合に設定
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
