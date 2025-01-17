import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom'; // 追加
import { uploadImage, updateUserProfile } from '../Component/api/profile';
import './css/Profileedit.css';

const Profileedit = ({ userid, username: initialUsername,profile: initialProfile, icon: initialIcon, onSave ,dialogOpen}) => {
  const [username, setUsername] = useState(initialUsername); // ユーザー名の状態
  const [profile, setProfile] = useState(initialProfile);
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
    const input = e.target.value;
    const sanitizedInput = input.replace(/[^a-zA-Z0-9]/g, "");
    setUsername(sanitizedInput);
    };

  // プロフィール文変更ハンドラ
  const handleProfileChange = (e) => {
    setProfile(e.target.value);
  };

  // 保存ボタンクリック時の処理
  const handleSave = async () => {
    try {

      if (icon) {
        const formData = new FormData();
        formData.append('avatar', icon); // アイコン画像を追加

        const url = `http://localhost:8080/users/${userid}/avatar`;
        // アイコン画像を送信
        await uploadImage(url,formData);
      }

      // ユーザー名を送信
      const url = `http://localhost:8080/users/${userid}`;
      const response = await updateUserProfile(url,username,profile);
      console.log(response);
      // 更新情報を親コンポーネントに渡す
      onSave({ id: response.user.id, username : response.user.username, profile : response.user.profile, user:response.user, icon: iconPreview },response.token);

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
                maxLength={14} // 14文字の制限を追加
              />
            </td>
          </tr>
          <tr className="profile-edit-profile-container">
            <td><p className="profile-text">プロフィールを編集:</p></td>
            <td>
              <textarea
                type="text"
                value={profile}
                onChange={handleProfileChange}
                className="profile-input"
              />
            </td>
          </tr>
        </table>
      </div>
    </dialog>
  );
};

export default Profileedit;
