import React, { useState, useRef, useEffect } from 'react';
import './css/UserFollowList.css';
import defaultAvatar from '../../images/tegakicreateuser.png'

const UserFollowList = ({ users, onFollowListClick }) => {
  const [isOpen, setIsOpen] = useState(false); // プルダウンの開閉状態を管理
  const dropdownRef = useRef(null); // ドロップダウンメニューを参照するための ref

  const toggleDropdown = () => {
    setIsOpen(!isOpen); // プルダウンを開閉
  };

  // ボタン外のクリックを検知して閉じる処理
  const handleClickOutside = (event) => {
    if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
      setIsOpen(false); // ドロップダウンメニューを閉じる
    }
  };

  useEffect(() => {
    // マウント時にクリックイベントを登録
    document.addEventListener('mousedown', handleClickOutside);

    // アンマウント時にイベントリスナーを削除
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  // 有効なユーザーIDをカウント
  const validUserCount = users ? users.filter((user) => user.id).length : 0;

  // リストが空の場合に適用するクラス名
  const dropdownClassName = validUserCount === 0 ? 'user-follow-list-empty' : 'user-follow-list';

  return (
    <div className="user-follow-list-container" ref={dropdownRef}>
      <button className="follow-list-button" onClick={toggleDropdown}>
        フォロー中（{validUserCount}人）
      </button>
      {isOpen && (
        <ul className={dropdownClassName}>
          {validUserCount > 0 ? (
            users.map((user) =>
              user.id ? ( // ユーザーに `id` がある場合のみ表示
                <li key={user.id} onClick={() => onFollowListClick(user)}>
                  <img
                    src={user.avatar_path ? `http://localhost:8080${user.avatar_path}` : defaultAvatar } 
                    alt={`${user.username}'s avatar`}
                  />
                  <span>{user.username}</span>
                </li>
              ) : null
            )
          ) : (
            // ユーザーが存在しない場合のメッセージ
            <span className="empty-follow-message">フォローしているユーザーはいません。</span>
          )}
        </ul>
      )}
    </div>
  );
};

export default UserFollowList;
