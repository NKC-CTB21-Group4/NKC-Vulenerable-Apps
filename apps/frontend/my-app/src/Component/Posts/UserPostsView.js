import React, { useEffect, useState } from 'react';
import {useParams, useNavigate } from 'react-router-dom'; // useNavigate をインポート
import { useFetchPosts, } from '../api/post';
import Contentinfo from '../ContentInfo/Contentinfo';
import './css/UserPostsView.css';
import defaultAvatar from '../../images/tegakicreateuser.png'


function UserPostsView({  }) {
  const [posts, setPosts] = useState([]);
  const navigate = useNavigate();
  const [isPrivate,setIsPrivate] = useState('');
  const { userid } = useParams(); // URLからユーザーIDを取得
  const {data,error,mutate} = useFetchPosts(`http://localhost:8080/users/${userid}/posts`);
  const [searchKeyword, setSearchKeyword] = useState('');
    
  useEffect(() => {
    if(data && posts.length){
      const postarray = Object.values(data).reverse();
      
      setPosts(postarray);
    }else{
      setIsPrivate("user private");
    };

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
      {isPrivate ? (
        <div className="private-message">ポストは、非公開です。</div>
      ):(
      posts.map((post) => (
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
      ))
      )}
    </div>
  );
}

export default UserPostsView;
