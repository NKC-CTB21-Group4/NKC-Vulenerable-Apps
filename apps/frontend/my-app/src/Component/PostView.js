import React, { useEffect, useState } from 'react';
import './css/PostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';

function PostView({}) {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
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
    fetchPosts();

    // newPostイベントをリスン
    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
    };

    window.addEventListener('newPost', handleNewPost);

     // カスタムイベントをリッスンして検索結果を取得する関数
     const handleSearchEvent = async (event) => {
      const { keyword, authorId, authorName, dateFrom, dateTo } = event.detail;

      // 検索パラメータをクエリストリングとして生成
      const queryParams = new URLSearchParams({
        keyword,
        authorId,
        authorName,
        dateFrom,
        dateTo
      });

      try {
        // 検索APIにリクエスト
        const response = await fetch(`http://localhost:8080/posts/search?${queryParams.toString()}`);
        const json = await response.json();
        const searchpostarray = Object.values(json.data)
        .reverse()  // 逆順にソート
        .filter((post) => post.deleted_at === null); // deleted_at が null の場合のみ
        setPosts(searchpostarray);
      } catch (error) {
        console.error('Error fetching search results:', error);
      }
    };

    // 検索イベントをリッスン
    window.addEventListener('SearchPost', handleSearchEvent);

    // クリーンアップ
    return () => {
      window.removeEventListener('newPost', handleNewPost);
      window.removeEventListener('SearchPost',handleSearchEvent);
    };
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
          imagepath={post.image_path}
          handleDelete={handleDelete}
        />
      ))}
    </div>
  );
}

export default PostView;
