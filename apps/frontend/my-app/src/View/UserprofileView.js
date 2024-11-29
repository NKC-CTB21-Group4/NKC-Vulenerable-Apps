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
import './css/UserprofileView.css'; 

function UserProfileView() {
    const { userid } = useParams(); // URLからユーザーIDを取得
    const { user } = useContext(AuthContext);
    const [userData, setUserData] = useState({}); // 指定ユーザーのデータを保存する状態
    const [searchKeyword, setSearchKeyword] = useState('');
    const navigate = useNavigate();
    const [followusers, setfollowUsers] = useState([]);
    const [followerusers, setfollowerUsers] = useState([]);
    const [isFollowed, setIsFollowed] = useState(false); // フォロー状態を管理
    

    const handleFollowListClick = (clickedUser) => {
      if (Number(clickedUser.id) === Number(user.id)) {
        navigate("/Mypage"); // ログイン中のユーザーならマイページに遷移
      } else {
        navigate(`/users/${clickedUser.id}/profile`); // 他のユーザーならプロフィールページへ遷移
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

    const handleUserFollowClick = async () => {
      try {
        const response = await fetch(`http://localhost:8080/users/${user.id}/follow/${userid}`, {
          method: isFollowed ? 'DELETE' : 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
          },
        });
  
        if (!response.ok) {
          throw new Error('認証に失敗しました');
        }


        const followResponse = await fetch(`http://localhost:8080/users/${userid}/follower`,{
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
          },
        });
        const followerUsers = await followResponse.json();
        if(Array.isArray(followerUsers.data)){
          const isAlreadyFollowed = followerUsers.data.some(followerUser => followerUser.id === Number(user.id));
          setIsFollowed(isAlreadyFollowed);
        }else setIsFollowed(false);
  
      } catch (error) {
      }
    };

    useEffect(() => {
        const fetchUserData = async () => {
          try {
            const response = await fetch(`http://localhost:8080/users/${userid}`);
            const data = await response.json();
            setUserData(data.data); 

            const followResponse = await fetch(`http://localhost:8080/users/${userid}/follower`,{
              headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('authToken')
              },
            });
            const followerUsers = await followResponse.json();
            if(Array.isArray(followerUsers.data)){
              const isAlreadyFollowed = followerUsers.data.some(followerUser => followerUser.id === Number(user.id));
              setIsFollowed(isAlreadyFollowed);
            }else setIsFollowed(false);
          } catch (error) {
            console.error('Error fetching user data:', error);
            
          }
        };
        fetchFollowList();
        fetchUserData();
        fetchFollowerList();
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
      src: (user?.avatar_path ? `http://localhost:8080${user.avatar_path}` : LinkiconCreateUser), // 更新されたアイコンを表示
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
    <header className="userpage-App-header">
      <div className="userpage-container">
        <Linkview links={links} onLinkClick={handleClearSearch} />
        <div className="userpage-header-posts-container">
          <Header />
          <div className="userpage-userinfo">
              <Profileinfo
                src={userData.avatar_path ? `http://localhost:8080${userData.avatar_path}` : Iconhavertz}
                username={userData.username}
                userid={userData.id}
                profile={userData.profile}
                onUserFollowClick={handleUserFollowClick}
                isFollowed={isFollowed}
                users={followusers}
                onFollowListClick={handleFollowListClick}
                followerusers={followerusers}
                onFollowerListClick={handleFollowListClick}
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
