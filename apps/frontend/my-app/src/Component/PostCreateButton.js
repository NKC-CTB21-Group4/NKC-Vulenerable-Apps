import React, { useState, useEffect, useContext } from 'react';
import './PostCreateButton.css'; // Assuming you have a CSS file for styling
import AuthContext from '../Utils/AuthProvider';

const PostCreateButton = () => {
  const [content, setContent] = useState(''); // State to hold textarea content
  const {user} = useContext(AuthContext);

  useEffect(() => {
    const postCreateBtn = document.querySelector("#post-create-btn");
    const postCreateModalBtn = document.querySelector("#post-create-modalBtn");
    const postCreateDialog = document.querySelector("#post-create-dialog");

    const openDialog = () => {
      postCreateDialog.showModal();
    };

    const closeDialog = () => {
      postCreateDialog.close();
    };

    postCreateBtn.addEventListener("click", openDialog);
    postCreateModalBtn.addEventListener("click", closeDialog);

    // Cleanup event listeners on component unmount
    return () => {
      postCreateBtn.removeEventListener("click", openDialog);
      postCreateModalBtn.removeEventListener("click", closeDialog);
    };
  }, []);

  const handlePostSubmit = async () => {
    try {
      const userId = user.id;
      const url = `http://localhost:8080/users/${userId}/posts`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
        body: JSON.stringify({
          content: content,
        }),
      });

      if (!response.ok) {
        throw new Error('Failed to create post');
      }

      const responseData = await response.json();
      console.log('Post created successfully:', responseData);
      // Optionally, you can handle the response data as needed

      // Clear textarea content after successful post
      setContent('');

      const postCreateDialog = document.querySelector("#post-create-dialog");
      postCreateDialog.close();
    } catch (error) {
      console.error('Error creating post:', error.message);
      // Handle error state or display error message to user
    }
  };

  return (
    <div>
      <button id="post-create-btn">クリック</button>
      <dialog id="post-create-dialog">
        <div>
          <p>モーダルです</p>
          <textarea
            id="post-crete-textArea"
            placeholder="ここにテキストを入力"
            value={content}
            onChange={(e) => setContent(e.target.value)}
          ></textarea>
          <button id="post-create-modalBtn">×</button>
          <button id="post-create-submitBtn" onClick={handlePostSubmit}>投稿</button>
        </div>
      </dialog>
    </div>
  );
}

export default PostCreateButton;
