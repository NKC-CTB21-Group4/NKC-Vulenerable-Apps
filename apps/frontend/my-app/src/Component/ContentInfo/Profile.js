import React from 'react';
import './css/Profile.css'; // CSSファイルをインポート

function Profile({ profile }) {
  return (
    <div className="profile-container">
      <div className="profile">{profile}</div>
    </div>
  );
}

export default Profile;
