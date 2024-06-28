import React, { useContext, useState } from 'react';
import './Uploadimage.css'; // CSSファイルをインポート
import AuthContext from '../Utils/AuthProvider';

const Uploadimage = () => {
  const { user } = useContext(AuthContext);
  const userId = user.id;
  const [file, setFile] = useState(null);
  const [message, setMessage] = useState('');
  const [imageUrl, setImageUrl] = useState(`http://localhost:8080/users/${userId}/avatar`);
  
  const handleFileChange = (event) => {
    setFile(event.target.files[0]);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (!file) {
      alert('ファイルを選択してください。');
      return;
    }

    const formData = new FormData();
    formData.append('avatar', file);

    try {
      const response = await fetch(`http://localhost:8080/users/${userId}/avatar`, {
        method: 'POST',
        headers: {
          "Authorization": 'Bearer ' + localStorage.getItem('authToken')
        },
        body: formData,
      });

      const result = await response.json();

      if (response.ok) {
        setMessage('アップロードに成功しました');
        setImageUrl(result.data);
      } else {
        setMessage(`Failed to upload avatar: ${result.message}`);
      }
    } catch (error) {
      console.error('Error:', error);
      setMessage('Network error occurred. Please try again later.');
    }
  };

  return (
    <div className="upload-container">
      <h3 className="upload-title">アイコンアップロードフォーム</h3>
      <form onSubmit={handleSubmit} className="upload-form">
        <label htmlFor="avatar" className="upload-label"></label>
        <input type="file" id="avatar" name="avatar" accept="image/*" onChange={handleFileChange} />
        <br />
        <button type="submit" className="upload-button">アップロード</button>
      </form>
      <div id="message">{message}</div>
    </div>
  );
};

export default Uploadimage;
