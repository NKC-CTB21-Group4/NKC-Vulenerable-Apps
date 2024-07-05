import React, { useState, useEffect, useContext } from 'react';
<<<<<<< HEAD
import './PostCreateButton.css'; // CSSファイルをインポート
=======
import './PostCreateButton.css'; // スタイリング用のCSSファイルを仮定
>>>>>>> 5bde2d1110362bdeed713d482e68884e5cd21c90
import AuthContext from '../Utils/AuthProvider';
import tegakiwrite from '../images/tegakiwrite.png'; // 画像ファイルをインポート

const PostCreateButton = () => {
<<<<<<< HEAD
  const [content, setContent] = useState(''); // State to hold textarea content
=======
  const [content, setContent] = useState(''); // テキストエリアの内容を保持する状態
  const [image, setImage] = useState(null); // 選択された画像ファイルを保持する状態
  const [imagePreview, setImagePreview] = useState(null); // 画像プレビューのための状態
  const [fileInputKey, setFileInputKey] = useState(0); // ファイル選択インプットのキー
>>>>>>> 5bde2d1110362bdeed713d482e68884e5cd21c90
  const { user } = useContext(AuthContext);

  useEffect(() => {
    const postCreateBtn = document.querySelector("#post-create-btn");
    const postCreateModalBtn = document.querySelector("#post-create-modalBtn");
    const postCreateDialog = document.querySelector("#post-create-dialog");

    const openDialog = () => {
      postCreateDialog.showModal();
    };

    const closeDialog = () => {
      postCreateDialog.close();
      setContent('');
      setImage(null);
      setImagePreview(null);
      setFileInputKey(prevKey => prevKey + 1); // キーを更新してファイル選択をリセット
    };

    postCreateBtn.addEventListener("click", openDialog);
    postCreateModalBtn.addEventListener("click", closeDialog);

    // コンポーネントのアンマウント時にイベントリスナーをクリーンアップ
    return () => {
      postCreateBtn.removeEventListener("click", openDialog);
      postCreateModalBtn.removeEventListener("click", closeDialog);
    };
  }, []);

  const handlePostSubmit = async () => {
    try {
      const userId = user.id;
      const url = `http://localhost:8080/users/${userId}/posts`;

      const formData = new FormData();
      formData.append('content', content);
      if (image) {
        formData.append('image', image);
      }

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
        body: formData,
      });

      if (!response.ok) {
        throw new Error('投稿の作成に失敗しました');
      }

      const responseData = await response.json();
      console.log('投稿が正常に作成されました:', responseData);
      // 必要に応じてレスポンスデータを処理

      // 成功した投稿後にテキストエリアと画像ファイルをクリア
      setContent('');
      setImage(null);
      setImagePreview(null);

      const postCreateDialog = document.querySelector("#post-create-dialog");
      postCreateDialog.close();
    } catch (error) {
      console.error('投稿の作成中にエラーが発生しました:', error.message);
      // エラーステートを処理するか、ユーザーにエラーメッセージを表示
    }
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImage(file);
      setImagePreview(URL.createObjectURL(file));
    } else {
      setImage(null);
      setImagePreview(null);
    }
  };

  const cancelImageUpload = () => {
    setImage(null);
    setImagePreview(null);
    const input = document.getElementById("post-create-fileInput");
    input.value = ''; // ファイル選択をリセット
  };

  return (
    <div>
      <img src={tegakiwrite} id="post-create-btn" alt="クリックボタン" />
      <dialog id="post-create-dialog">
        <div>
          <p>ポストの作成</p>
          {imagePreview && (
            <div className="image-preview">
              <img src={imagePreview} alt="選択された画像プレビュー" />
              <button className="cancel-upload-button" onClick={cancelImageUpload}>キャンセル</button>
            </div>
          )}
          <div className="custom-file-input">
            <label htmlFor="post-create-fileInput">
              <img
                src="path_to_your_image.png"
                alt="ファイルを選択"
              />
            </label>
            <input
              key={fileInputKey}
              type="file"
              accept="image/*"
              id="post-create-fileInput"
              style={{ display: 'none' }}
              onChange={handleImageChange}
            />
          </div>
          <textarea
            id="post-create-textArea"
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
