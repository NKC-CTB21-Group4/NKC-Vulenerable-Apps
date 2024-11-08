import React, { useContext, useEffect, useState } from 'react';
import './css/MyPostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';
import AuthContext from '../Utils/AuthProvider';

function MyPostView({ render}) { // デフォルト値として空の配列を設定
  const [posts, setPosts] = useState([]); 
  const { user } = useContext(AuthContext);
  const userid = user?.id;

  useEffect(() => {
    // 初回読み込み時にユーザーの投稿を取得する関数
    const fetchUserPosts = async () => {
      if (!userid) return;
      try {
        const response = await fetch(`http://localhost:8080/users/${userid}/posts`);
        const json = await response.json();
        const mypostarray = Object.values(json.data).reverse(); // 逆順にソート
        setPosts(mypostarray);
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    };

    // 初回読み込み
    fetchUserPosts();

    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
    };

    window.addEventListener('newPost', handleNewPost);


    // カスタムイベントをリッスンして検索結果を取得する関数
    const handleSearchEvent = async (event) => {
      const { keyword, authorName, dateFrom, dateTo } = event.detail;

      // 検索パラメータをクエリストリングとして生成
      const queryParams = new URLSearchParams({
        keyword,
        authorId:userid,
        authorName,
        dateFrom,
        dateTo
      });

      try {
        // 検索APIにリクエスト
        const response = await fetch(`http://localhost:8080/posts/search?${queryParams.toString()}`);
        const json = await response.json();
        const searchpostarray = Object.values(json.data)
        .reverse()// 逆順にソート
        .filter((post) => post.deleted_at === null); // deleted_at が null の場合のみ
        setPosts(searchpostarray);
      } catch (error) {
        console.error('Error fetching search results:', error);
      }
    };

    // 検索イベントをリッスン
    window.addEventListener('SearchPost', handleSearchEvent);

    // クリーンアップ：コンポーネントのアンマウント時にイベントリスナーを削除
    return () => {
      window.removeEventListener('SearchPost', handleSearchEvent);
      window.removeEventListener('newPost', handleNewPost);
    };
  },[render]);

  // 投稿削除時にリストを更新する関数
  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  return (
    <div className="mypostview-container">
      {posts.length > 0 ? (
        posts.map((post) => (
          <Contentinfo
            key={post.id}
            src={`http://localhost:8080${post.author_avatar}`} // srcとaltはUserinfoコンポーネントが使っている場合に設定
            alt=""
            username={post.author_name}
            userid={post.author_id}
            content={post.content}
            postid={post.id}
            imagepath={post.image_path}
            handleDelete={handleDelete} // handleDelete関数のプロップス名を修正
          />
        ))
      ) : (
        <p>表示する投稿がありません。</p>
      )}
    </div>
  );
}

export default MyPostView;
