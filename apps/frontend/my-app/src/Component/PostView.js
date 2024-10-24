import React, { useState, useEffect,useContext } from 'react';
import './css/PostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';
import { useFetchPosts } from '../api/post';
import AuthContext from '../Utils/AuthProvider';

function PostView({ searchKeyword }) {
  const [posts, setPosts] = useState([]);
  const { data, error, mutate } = useFetchPosts('http://localhost:8080/posts');
  const { user } = useContext(AuthContext);
  const userid = user?.id;

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
        /*
        rollbackOnError: true, // エラーが発生した場合にロールバック
        revalidate: true, // サーバーからの最新データを再フェッチ
        */
      });
    };

    window.addEventListener('newPost', handleNewPost);

    return () => {
      window.removeEventListener('newPost', handleNewPost);
    };
  }, []);

  if (error) return <div>Failed to load</div>;
  if (!data) return <div>Loading...</div>;

  const handleDelete = async(postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
    
    await mutate(`http://localhost:8080/users/${userid}/posts`);
    await mutate('http://localhost:8080/posts'); // PostViewのキャッシュも更新
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
