import React, { useState, useEffect, useContext } from 'react';
import './PostCreateButton.css'; // Assuming you have a CSS file for styling
import AuthContext from '../Utils/AuthProvider';

const PostCreateButton = () => {
  const [content, setContent] = useState(''); // State to hold textarea content
  const {user} = useContext(AuthContext);

  useEffect(() => {
    const postcreatebtn = document.querySelector("#postcreatebtn");
    const postcreatemodalBtn = document.querySelector("#postcreatemodalBtn");
    const postcreatedialog = document.querySelector("#postcreatedialog");

    const openDialog = () => {
      postcreatedialog.showModal();
    };

    const closeDialog = () => {
      postcreatedialog.close();
    };

    postcreatebtn.addEventListener("click", openDialog);
    postcreatemodalBtn.addEventListener("click", closeDialog);

    // Cleanup event listeners on component unmount
    return () => {
      postcreatebtn.removeEventListener("click", openDialog);
      postcreatemodalBtn.removeEventListener("click", closeDialog);
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

      const postcreatedialog = document.querySelector("#postcreatedialog");
      postcreatedialog.close();
    } catch (error) {
      console.error('Error creating post:', error.message);
      // Handle error state or display error message to user
    }
  };

  return (
    <div>
      <button id="postcreatebtn">クリック</button>
      <dialog id="postcreatedialog">
        <div>
          <p>モーダルです</p>
          <textarea
            id="postcretetextArea"
            placeholder="ここにテキストを入力"
            value={content}
            onChange={(e) => setContent(e.target.value)}
          ></textarea>
          <button id="postcreatemodalBtn">×</button>
          <button id="postcreatesubmitBtn" onClick={handlePostSubmit}>投稿</button>
        </div>
      </dialog>
    </div>
  );
}

export default PostCreateButton;
