import React, { useEffect, useState } from 'react';
import {useParams, useNavigate } from 'react-router-dom'; // useNavigate をインポート
import Contentinfo from './ContentInfo/Contentinfo';
import './css/UserPostsView.css';


function UserPostsView({  }) {
  const [posts, setPosts] = useState([]);
  const navigate = useNavigate();
  const [isprivate, setisPrivate] = useState(''); // エラーメッセージ用のステート
  const { userid } = useParams(); // URLからユーザーIDを取得
  const [searchKeyword, setSearchKeyword] = useState('');
    
  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const authtoken = localStorage.getItem('authToken');
          const response = await fetch(`http://localhost:8080/users/${userid}/posts`,authtoken != undefined ? {
            headers:{
              'Authorization': `Bearer ${authtoken}`,
            }
          } : {});
        const json = await response.json();
        const postarray = Object.values(json.data).reverse(); // 逆順にソート
        if (response.status === 404 && json.data === "This follower is private.") {
          // プライベートユーザーの場合
          setisPrivate("user private");
        }
        setPosts(postarray);
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    };
    fetchPosts();


    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
    };

    window.addEventListener('newPost', handleNewPost);

    const handleSearchEvent = async (event) => {
    const { keyword, authorId, authorName, dateFrom, dateTo } = event.detail;

      // 検索パラメータをクエリストリングとして生成
      const queryParams = new URLSearchParams({
        keyword,
        authorId: userid,
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
    window.addEventListener('newPost', handleNewPost);
    // クリーンアップ
    return () => {
      window.removeEventListener('newPost', handleNewPost);
      window.removeEventListener('SearchPost',handleSearchEvent);
    };
  }, []);

  

  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  const handleUserIconClick = (userid) => {
    navigate(`/users/${userid}/profile`)
  };

  

  return (
    <div className="User-postview-container">
       {isprivate ? (
        // エラーメッセージがある場合はその内容を表示
        <div className="private-message">ポストは非公開です。</div>
      ) : (
        // 投稿がある場合は投稿を表示
        posts.map((post) => (
          <Contentinfo
            key={post.id}
            src={`http://localhost:8080${post.author_avatar}`}
            alt=""
            username={post.author_name}
            userid={post.author_id}
            content={post.content}
            postid={post.id}
            imagepath={post.image_path}
            handleDelete={handleDelete}
            onUserIconClick={handleUserIconClick} // アイコンをクリックした際に呼び出す関数を渡す
          />
        ))
      )}
    </div>
  );
}

export default UserPostsView;
