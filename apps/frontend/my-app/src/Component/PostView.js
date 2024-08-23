import React, { useState, useEffect,useContext } from 'react';
import './css/PostView.css'; // CSSファイルをインポート
import Contentinfo from './ContentInfo/Contentinfo';
import { useFetchPosts } from '../api/post';

function PostView({ searchKeyword }) {
  const [posts, setPosts] = useState([]);
  const { data, error, mutate } = useFetchPosts('http://localhost:8080/posts');

  useEffect(() => {
    if (data && posts.length === 0) {
      const postArray = Object.values(data.data).reverse();
      setPosts(postArray);
    }
  }, [data, posts.length]);

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

  const handleDelete = async(postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
    await mutate('http://localhost:8080/posts');
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
