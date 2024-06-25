import React, { useState, useEffect, useContext } from 'react';
import './PostCreateButton.css'; // Assuming you have a CSS file for styling
import AuthContext from '../Utils/AuthProvider';

const PostCreateButton = () => {
  const [content, setContent] = useState(''); // State to hold textarea content
  const {user} = useContext(AuthContext);

  useEffect(() => {
    const btn = document.querySelector("#btn");
    const modalBtn = document.querySelector("#modalBtn");
    const dialog = document.querySelector("#dialog");

    const openDialog = () => {
      dialog.showModal();
    };

    const closeDialog = () => {
      dialog.close();
    };

    btn.addEventListener("click", openDialog);
    modalBtn.addEventListener("click", closeDialog);

    // Cleanup event listeners on component unmount
    return () => {
      btn.removeEventListener("click", openDialog);
      modalBtn.removeEventListener("click", closeDialog);
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

      const dialog = document.querySelector("#dialog");
      dialog.close();
    } catch (error) {
      console.error('Error creating post:', error.message);
      // Handle error state or display error message to user
    }
  };

  return (
    <div>
      <button id="btn">クリック</button>
      <dialog id="dialog">
        <div>
          <p>モーダルです</p>
          <textarea
            id="textArea"
            placeholder="ここにテキストを入力"
            value={content}
            onChange={(e) => setContent(e.target.value)}
          ></textarea>
          <button id="modalBtn">×</button>
          <button id="submitBtn" onClick={handlePostSubmit}>投稿</button>
        </div>
      </dialog>
    </div>
  );
}

export default PostCreateButton;
