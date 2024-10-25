import React, { useContext, useEffect, useState } from 'react';
import './css/MyPostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';
import AuthContext from '../Utils/AuthProvider';

function MyPostView({ searchKeyword ,render}) { // デフォルト値として空の配列を設定
  const [posts, setPosts] = useState([]); 
  const { user } = useContext(AuthContext);
  const userid = user?.id;

  useEffect(() => {
    const fetchPosts = async () => {
      if(!userid)return;
      try {
        const response = await fetch(`http://localhost:8080/users/${userid}/posts`);
        const json = await response.json();
        const mypostarray = Object.values(json.data).reverse(); // 逆順にソート
        setPosts(mypostarray);
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    };

    fetchPosts();

    // newPostイベントをリスン
    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
    };

    window.addEventListener('newPost', handleNewPost);

    // クリーンアップ
    return () => {
      window.removeEventListener('newPost', handleNewPost);
    };
  },[render]);

  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  const filteredPosts = posts.filter(post => 
    post.content.toLowerCase().includes(searchKeyword.toLowerCase()) ||
    post.author_name.toLowerCase().includes(searchKeyword.toLowerCase())
  );

  return (
    <div className="mypostview-container">
      {filteredPosts.map((post) => (
        <Contentinfo
          key={post.id}
          src={`http://localhost:8080${post.author_avatar}`}// srcとaltはUserinfoコンポーネントが使っている場合に設定
          alt=""
          username={post.author_name}
          userid={post.author_id}
          content={post.content}
          postid={post.id}
          imagepath={post.image_path}
          handleDelete={handleDelete} // handleDelete関数のプロップス名を修正
        />
      ))}
    </div>
  );
}

export default MyPostView;
