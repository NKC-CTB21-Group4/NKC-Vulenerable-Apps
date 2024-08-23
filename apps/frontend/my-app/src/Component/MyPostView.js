import React, { useContext, useEffect, useState } from 'react';
import './css/MyPostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';
import AuthContext from '../Utils/AuthProvider';
import { useFetchPosts } from '../api/post';

function MyPostView({ searchKeyword }) { // デフォルト値として空の配列を設定
  const [posts, setPosts] = useState([]); 
  const { user } = useContext(AuthContext);
  const userid = user?.id;
  const { data, error, mutate } = useFetchPosts(`http://localhost:8080/users/${userid}/posts`);

  // データが取得できたときに、postsステートを更新
  if (data && posts.length === 0) {
    const postArray = Object.values(data.data).reverse();
    setPosts(postArray);
  }
  
  useEffect(() => {
    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
    };

    window.addEventListener('newPost', handleNewPost);

    return () => {
      window.removeEventListener('newPost', handleNewPost);
    };
  }, []);

  if (error) return <div>Failed to load</div>;
  if (!data) return <div>Loading...</div>;

  const handleDelete = async (postid) => {
    // 状態を手動で更新
    setPosts(posts.filter((post) => post.id !== postid));
    // mutate関数でサーバーから最新のデータを取得
    await mutate(`http://localhost:8080/users/${userid}/posts`);
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
          src={`http://localhost:8080/users/${userid}/avatar`}// srcとaltはUserinfoコンポーネントが使っている場合に設定
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
