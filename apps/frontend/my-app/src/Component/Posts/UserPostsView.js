import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useFetchPosts, searchPosts } from '../api/post';
import Contentinfo from '../ContentInfo/Contentinfo';
import './css/UserPostsView.css';
import defaultAvatar from '../../images/tegakicreateuser.png';

function UserPostsView() {
  const [posts, setPosts] = useState([]);
  const navigate = useNavigate();
  const [isPrivate, setIsPrivate] = useState('');
  const { userid } = useParams();
  const { data = null, error, mutate } = useFetchPosts(`http://localhost:8080/users/${userid}/posts`, "UserPostsView.js");

  useEffect(() => {
    if (data) {
      if (data.statusCode === 403) {
        setIsPrivate("user private");
      } else if (data.data) {
        const postarray = Object.values(data.data).reverse();
        setPosts(postarray);
      }
    }
  }, [data]);

  useEffect(() => {
    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
    };

    const handleSearchEvent = async (event) => {
      const { keyword, authorId, authorName, dateFrom, dateTo } = event.detail;
      const queryParams = new URLSearchParams({
        keyword,
        authorId: userid,
        authorName,
        dateFrom,
        dateTo,
      });

      try {
        const url = `http://localhost:8080/posts/search?${queryParams.toString()}`;
        const response = await searchPosts(url);
        const searchpostarray = Object.values(response)
          .reverse()
          .filter((post) => post.deleted_at === null);
        setPosts(searchpostarray);
      } catch (error) {
        console.error('Error fetching search results:', error);
      }
    };

    window.addEventListener('newPost', handleNewPost);
    window.addEventListener('SearchPost', handleSearchEvent);

    return () => {
      window.removeEventListener('newPost', handleNewPost);
      window.removeEventListener('SearchPost', handleSearchEvent);
    };
  }, [userid]);

  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  const handleUserIconClick = (userid) => {
    navigate(`/users/${userid}/profile`);
  };

  return (
    <div className="User-postview-container">
      {isPrivate ? (
        <div className="private-message">ポストは、非公開です。</div>
      ) : (
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
            onUserIconClick={handleUserIconClick}
          />
        ))
      )}
    </div>
  );
}

export default UserPostsView;
