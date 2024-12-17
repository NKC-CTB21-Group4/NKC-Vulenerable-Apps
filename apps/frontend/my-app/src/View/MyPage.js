import React, { useContext, useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './css/MyPage.css'; // CSSファイルをインポート
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
import MyPostview from '../Component/MyPostView';
import Header from '../Component/Common/Header';
import AuthContext from '../Utils/AuthProvider';
import Logout from '../Component/Common/Logout';
import Search from '../Component/Search';
import Profileinfo from '../Component/ContentInfo/Profileinfo';
import LinkiconProfileedit from '../images/haguruma.png';
import Profileedit from './Profileedit';
 
function MyPage() {
  const { user, isAuthenticated, updateUser,updateToken } = useContext(AuthContext);
  const [searchKeyword, setSearchKeyword] = useState('');
  const [isProfileEditOpen, setIsProfileEditOpen] = useState(false); // プロフィール編集モーダルの状態管理
  const [updatedUsername, setUpdatedUsername] = useState(user?.username); // ユーザー名の更新用状態
  const [updatedProfile, setUpdatedProfile] = useState(user?.profile); // ユーザー名の更新用状態
  const [followusers, setfollowUsers] = useState([]);
  const [followerusers, setfollowerUsers] = useState([]);
  const [updatedIcon, setUpdatedIcon] = useState(null); // アイコンの更新用状態
  const [render,setRender] = useState(0); //MyPostView再レンダリング用
  const [isPrivated, setIsPrivated] = useState(false); // 鍵垢状態を管理
  

  const userid = user?.id;
  const username = updatedUsername || user?.username; // 更新されたユーザー名を表示
  const profile = updatedProfile || user?.profile; // 更新されたユーザー名を表示
  const userAvatarPath = user?.avatar_path;
  const navigate = useNavigate();



  const handleUserPrivateClick = async () => {
    try {
      // プライベート設定のトグル操作
      const response = await fetch(`http://localhost:8080/users/${user.id}/private`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
        },
      });
  
      if (!response.ok) {
        throw new Error('プライベート設定の更新に失敗しました');
      }
  
      // プライベート状態の取得
      const privateResponse = await fetch(`http://localhost:8080/users/${user.id}`, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
        },
      });
  
      if (!privateResponse.ok) {
        throw new Error('ユーザーデータの取得に失敗しました');
      }
  
      const privateData = await privateResponse.json();
      const isPrivate = privateData?.data?.is_private; // `is_private` プロパティを直接確認
      setIsPrivated(isPrivate); // 状態を更新
    } catch (error) {
      console.error('Error handling user private click:', error);
    }
  };
  
  
  const handleFollowListClick = (user) => {
    navigate(`/users/${user.id}/profile`)
  };

  const fetchprivate = async () => {
    try {
      const privateResponse = await fetch(`http://localhost:8080/users/${userid}`, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
      });
      const privateData = await privateResponse.json();
      const isPrivate = privateData?.data?.is_private; // `is_private` プロパティを直接確認
      setIsPrivated(isPrivate); // 状態を更新
    } catch (error) {
      console.error('Error handling user private click:', error);
    }
  };


  const fetchFollowList = async () => {
    try {
      const response = await fetch(`http://localhost:8080/users/${userid}/followed`, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
      });
      const followingUsers = await response.json();
      setfollowUsers(Array.isArray(followingUsers.data) ? followingUsers.data : []); // フォローリストを保存
    } catch (error) {
      console.error('Error fetching follow list:', error);
    }
  };
 
  const fetchFollowerList = async () => {
    try {
      const response = await fetch(`http://localhost:8080/users/${userid}/follower`, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
      });
      const followerUsers = await response.json();
      setfollowerUsers(Array.isArray(followerUsers.data) ? followerUsers.data :  []); // フォロワーリストを保存
    } catch (error) {
      console.error('Error fetching follow list:', error);
    }
  };
 
  useEffect(() => {
    if (!isAuthenticated) {
      navigate('/');
    }
    const fetchUserData = async () => {
      try {
        const followlistResponse = await fetch(`http://localhost:8080/users/${user?.id}/followed`,{
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
          },
        });
        const followingUsers = await followlistResponse.json();
        setfollowUsers(followingUsers);
      }catch (error) {
        console.error('Error fetching user data:', error);
        navigate('/'); // エラーがあればホームページにリダイレクト
      }
    }
    fetchUserData();
    fetchFollowList();
    fetchFollowerList();
    fetchprivate();
  }, [isAuthenticated, navigate,user]);

  // プロフィール編集モーダルを開く
  const handleProfileEditOpen = () => {
    setIsProfileEditOpen(true);
  };

  // プロフィール編集モーダルを閉じる
  const handleProfileEditClose = () => {
    setIsProfileEditOpen(false);
  };

  // プロフィール情報が保存されたときの処理
  const handleProfileSave = (updatedUser,token) => {
    setUpdatedUsername(updatedUser.username);
    setUpdatedProfile(updatedUser.profile);
    setUpdatedIcon(updatedUser.icon); // 新しいアイコンがアップロードされた場合、そのプレビューURLを設定
    updateUser(updatedUser.user);
    updateToken(token);
    setRender(render+1);
    handleProfileEditClose();
  };

  const handleUserIconClick = () => {
    navigate("/Mypage")
  };

  

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
      src: updatedIcon || (userAvatarPath ? `http://localhost:8080${userAvatarPath}` : LinkiconCreateUser), // 更新されたアイコンを表示
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
    <header className='mypage-App-header'>
      <div className="mypage-container">
        <Linkview links={links} onLinkClick={handleClearSearch} />
        <div className="mypage-header-posts-container">
          <Header />
          <div className="mypage-userinfo">
            <Profileinfo
              src={updatedIcon || (userAvatarPath ? `http://localhost:8080${userAvatarPath}` : LinkiconCreateUser)} // 更新されたアイコンを表示
              username={username}
              userid={userid}
              profile={profile}
              onUserIconClick={handleUserIconClick}
              users={followusers.length > 0 ? followusers : null}
              onFollowListClick={handleFollowListClick}
              followerusers={followerusers.length > 0 ? followerusers : null}
              onFollowerListClick={handleFollowListClick}
              onUserPrivateClick={handleUserPrivateClick}
              isPrivated={isPrivated}
            />
            <div className="profile-edit">
              <img
                className="profile-edit-icon"
                src={LinkiconProfileedit}
                alt="プロフィール編集"
                onClick={handleProfileEditOpen} // アイコンクリックでモーダルを開く
              />
            </div>
          </div>
          <MyPostview searchKeyword={searchKeyword} render={render}/>
        </div>
        <Search setSearchKeyword={setSearchKeyword} searchKeyword={searchKeyword} />
      </div>
      <Logout />

      {/* プロフィール編集モーダル */}
      {isProfileEditOpen && (
        <Profileedit
          userid={userid}
          username={username}
          profile={profile}
          icon={updatedIcon || (userid ? `http://localhost:8080${userAvatarPath}` : Iconhavertz)}
          onSave={handleProfileSave} // 保存時の処理を設定
          dialogOpen={setIsProfileEditOpen}
        />
      )}
    </header>
  );
}

export default MyPage;

