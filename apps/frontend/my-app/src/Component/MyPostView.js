import React, { useContext, useEffect, useState } from 'react';
import './css/MyPostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';
import AuthContext from '../Utils/AuthProvider';
import { useFetchPosts } from '../api/post';

function MyPostView({ searchKeyword }) { 
  const [posts, setPosts] = useState([]); 
  const { user } = useContext(AuthContext);
  const userid = user?.id;
  const { data, error, mutate } = useFetchPosts(`http://localhost:8080/users/${userid}/posts`);

  // データが取得できたら、postsステートを更新
  useEffect(() => {
    if (data && posts.length === 0) {
      const postArray = Object.values(data).reverse();
      setPosts(postArray.flat()); // flat()を使って二重配列を解消
    }
  }, [data, posts.length]);  

  // 新しい投稿が追加された時に、postsステートを楽観的に更新
  useEffect(() => {
    const handleNewPost = (event) => {
      const newPost = event.detail;

      // 楽観的にUIを更新する
      setPosts((prevPosts) => [newPost, ...prevPosts]);
      // mutateで最新のデータを再取得する（サーバー応答後に更新）
      mutate('http://localhost:8080/posts');
      mutate(`http://localhost:8080/users/${userid}/posts`, {
        data: [newPost, ...posts], // 楽観的なデータ
      });
    };

    window.addEventListener('newPost', handleNewPost);

    return () => {
      window.removeEventListener('newPost', handleNewPost);
    };
  }, []);

  if (error) return <div>Failed to load</div>;
  if (!data) return <div>Loading...</div>;

  const handleDelete = async (postid) => {
    // 投稿を一旦削除（楽観的にUI更新）
    setPosts(posts.filter((post) => post.id !== postid));
    // mutate関数でサーバーから最新のデータを取得
    await mutate(`http://localhost:8080/users/${userid}/posts`);
    await mutate('http://localhost:8080/posts'); // PostViewのキャッシュも更新
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
          src={`http://localhost:8080/users/${userid}/avatar`} // srcとaltはUserinfoコンポーネントが使っている場合に設定
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
