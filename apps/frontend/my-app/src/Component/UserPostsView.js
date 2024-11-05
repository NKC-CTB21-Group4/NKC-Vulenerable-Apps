import React, { useEffect, useState } from 'react';
import {useParams, useNavigate } from 'react-router-dom'; // useNavigate をインポート
import Contentinfo from './ContentInfo/Contentinfo';

function UserPostsView({  }) {
  const [posts, setPosts] = useState([]);
  const navigate = useNavigate();
  const { userid } = useParams(); // URLからユーザーIDを取得
  const [searchKeyword, setSearchKeyword] = useState('');
    
  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const response = await fetch(`http://localhost:8080/users/${userid}/posts`);
        const json = await response.json();
        const postarray = Object.values(json.data).reverse(); // 逆順にソート
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

    return () => {
      window.removeEventListener('newPost', handleNewPost);
    };
  }, []);

  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  const handleUserIconClick = (userid) => {
    navigate(`/users/${userid}/profile`)
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
      ))}
    </div>
  );
}

export default UserPostsView;
