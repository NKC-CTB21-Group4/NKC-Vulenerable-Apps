import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom'; // useNavigate をインポート
import { useFetchPosts, usesearchPost } from '../api/post';
import './css/PostView.css';
import Contentinfo from '../ContentInfo/Contentinfo';
import defaultAvatar from '../../images/tegakicreateuser.png'

function PostView({}) {
  const [posts, setPosts] = useState([]);
  const {data, error, mutate} = useFetchPosts(`http://localhost:8080/posts`);
  const navigate = useNavigate();

  useEffect(() =>{
    if(data && posts.length === 0){
      const postarray = Object.values(data).reverse(); // 逆順にソート
      setPosts(postarray.flat());
      } 
  },[data])

  useEffect(() => {
    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
      mutate(`http://localhost:8080/posts`);
    };

     // カスタムイベントをリッスンして検索結果を取得する関数
     const handleSearchEvent = async (event) => {
      const { keyword, authorId, authorName, dateFrom, dateTo, onlyFromFollowedUser } = event.detail;

      // 検索パラメータをクエリストリングとして生成
      const queryParams = new URLSearchParams({
        keyword,
        authorId,
        authorName,
        dateFrom,
        dateTo,
        onlyFromFollowedUser
      });

      // 検索APIにリクエスト
      const url = `http://localhost:8080/posts/search?${queryParams.toString()}`
      const response = await usesearchPost(url);
      if(response){
        setPosts(response);
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
    <div className="postview-container">
      {posts.map((post) => (
        <Contentinfo
          key={post.id}
          src={post.author_avatar ? `http://localhost:8080${post.author_avatar}` : defaultAvatar}
          alt=""
          username={post.author_name}
          userid={post.author_id}
          content={post.content}
          postid={post.id}
          imagepath={post.image_path}
          handleDelete={handleDelete}
          onUserIconClick={handleUserIconClick} // アイコンをクリックした際に呼び出す関数を渡す
        />
      ))}
    </div>
  );
}

export default PostView;
