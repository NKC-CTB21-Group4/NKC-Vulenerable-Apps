import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom'; // 追加
import './css/Profileedit.css';

const Profileedit = ({ userid, username: initialUsername, icon: initialIcon, onSave ,dialogOpen}) => {
  const [username, setUsername] = useState(initialUsername); // ユーザー名の状態
  const [icon, setIcon] = useState(null); // 新しく選択されたアイコン画像
  const [iconPreview, setIconPreview] = useState(initialIcon); // 画像プレビューの状態
  const authtoken = localStorage.getItem('authToken');
  const navigate = useNavigate(); // useNavigateフックの使用

  // アイコンが変更されたときのハンドラ
  const handleIconChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setIcon(file); // ファイルを設定
      setIconPreview(URL.createObjectURL(file)); // 画像のプレビューURLを作成
    }
  };

  // ユーザー名変更ハンドラ
  const handleUsernameChange = (e) => {
    setUsername(e.target.value);
  };

  // 保存ボタンクリック時の処理
  const handleSave = async () => {
    try {
      // ユーザー名を送信
      const response = await fetch(`http://localhost:8080/users/${userid}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${authtoken}`,
        },
        body: JSON.stringify({ username }), // ユーザー名を送信
      });
      const json = (await response.json()).data;

      if (icon) {
        const formData = new FormData();
        formData.append('avatar', icon); // アイコン画像を追加

        // アイコン画像を送信
        await fetch(`http://localhost:8080/users/${userid}/avatar`, {
          method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
        body: formData,
        });
      }
      console.log(json);
      // 更新情報を親コンポーネントに渡す
      onSave({ id: json.user.id, username : json.user.username, user:json.user, icon: iconPreview },json.token);

      // 保存後にMypageへリダイレクト
      navigate('/mypage');
    } catch (error) {
      console.error('保存中にエラーが発生しました:', error);
    }
  };

  // モーダルの閉じる処理（保存せずに閉じる）
  const handleCancel = () => {
    dialogOpen(false);
  };

  return (
    <dialog id="profile-edit-dialog" open>
      <div className="profile-edit-container">
        {/* ボタンセクション */}
        <div className="profile-edit-button-container">
          <button onClick={handleCancel} className="cancel-button">キャンセル</button>
          <button onClick={handleSave} className="save-button">保存</button>
        </div>
        <h2>編集</h2>
        <table>
          {/* アイコン編集 */}
          <tr className="profile-edit-icon-container">
            <td>{iconPreview && <img src={iconPreview} alt="アイコンを変更：" className="icon-preview" />}</td>
            <td>
              <input
                src={iconPreview}
                type="file"
                accept="image/*"
                onChange={handleIconChange}
              />
            </td>
          </tr>
          
          {/* ユーザー名編集 */}
          <tr className="profile-edit-username-container">
            <td><p className="username-text">ユーザー名を変更:</p></td>
            <td>
              <input
                type="text"
                value={username}
                onChange={handleUsernameChange}
                className="username-input"
              />
            </td>
          </tr>
        </table>
      </div>
    </dialog>
  );
};

export default Profileedit;
