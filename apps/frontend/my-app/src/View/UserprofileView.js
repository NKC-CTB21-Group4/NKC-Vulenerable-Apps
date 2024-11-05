import React, { useContext, useEffect, useState } from 'react';
import {useParams,  useNavigate } from 'react-router-dom';
import LinkiconHome from '../images/tegakihome.png';
import LinkiconBell from '../images/tegakibell.png';
import LinkiconMassage from '../images/tegakimessage.png';
import Iconhavertz from '../images/havertz.png';
import Linkiconbutton from '../images/tegakibutton.png';
import LinkiconLogin from '../images/tegakilogin.png';
import LinkiconCreateUser from '../images/tegakicreateuser.png';
import LinkiconLogout from '../images/tegakilogout.png';
import LinkiconApp from '../images/tegakiappicon.png';
import Linkview from '../Component/LinkView/Linkview';
import Header from '../Component/Header';
import AuthContext from '../Utils/AuthProvider';
import Logout from '../Component/Logout';
import Search from '../Component/Search';
import Profileinfo from '../Component/ContentInfo/Profileinfo';
import UserPostsView from '../Component/UserPostsView';

function UserProfileView() {
    const { userid } = useParams(); // URLからユーザーIDを取得
    const { auth } = useContext(AuthContext);
    const [userData, setUserData] = useState({}); // 指定ユーザーのデータを保存する状態
    const [searchKeyword, setSearchKeyword] = useState('');
    const navigate = useNavigate();

    useEffect(() => {
        const fetchUserData = async () => {
          try {
            const response = await fetch(`http://localhost:8080/users/${userid}`);
            const data = await response.json();
            setUserData(data.data); 
          } catch (error) {
            console.error('Error fetching user data:', error);
            navigate('/'); // エラーがあればホームページにリダイレクト
          }
        };
    
        fetchUserData();
      }, [userid]);

  const handleClearSearch = () => {
    setSearchKeyword('');
  };

  const links = [
    {
      src: LinkiconApp,
      alt: 'Icon',
      to: '/'
    },
    {
      src: LinkiconHome,
      alt: 'User Icon',
      to: '/',
      text: "ホーム",
      onClick: handleClearSearch
    },
    {
      src: Linkiconbutton,
      alt: 'Linkicon1',
      to: '/',
      text: "通報"
    },
    {
      src: LinkiconBell,
      alt: 'Linkicon4',
      to: '',
      text: "通知"
    },
    {
      src: LinkiconMassage,
      alt: 'Linkicon1',
      to: '/dm',
      text: "メッセージ"
    },
    {
      src: (auth?.avatar_path ? `http://localhost:8080${userData.avatar_path}` : Iconhavertz), // 更新されたアイコンを表示
      alt: 'Linkicon1',
      to: '/Mypage',
      text: "マイページ"
    }
  ];

  if (!userid) {
    links.push(
      {
        src: LinkiconLogin,
        alt: 'LinkiconLogin',
        to: '/login',
        text: "ログイン"
      },
      {
        src: LinkiconCreateUser,
        alt: 'LinkiconCreateUser',
        to: '/signup',
        text: "新規登録"
      }
    );
  } else {
    links.push(
      {
        src: LinkiconLogout,
        alt: 'LinkiconLogout',
        to: '#',
        text: "ログアウト",
        onClick: () => {
          const logoutDialog = document.querySelector("#logout-dialog");
          if (logoutDialog) {
            logoutDialog.showModal();
          }
        }
      }
    );
  }

  return (
    <header className="mypage-App-header">
      <div className="mypage-container">
        <Linkview links={links} onLinkClick={handleClearSearch} />
        <div className="mypage-header-posts-container">
          <Header />
          <div className="mypage-userinfo">
              <Profileinfo
                src={userData.avatar_path ? `http://localhost:8080${userData.avatar_path}` : Iconhavertz}
                username={userData.username}
                userid={userData.id}
                profile={userData.profile}
              />
          </div>
          <UserPostsView /> {/* 他のユーザーの投稿表示 */}
        </div>
        <Search setSearchKeyword={setSearchKeyword} searchKeyword={searchKeyword} />
      </div>
      <Logout />
    </header>
  );
}

export default UserProfileView;
