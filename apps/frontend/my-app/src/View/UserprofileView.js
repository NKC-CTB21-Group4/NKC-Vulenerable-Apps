import React, { useContext, useEffect, useState } from 'react';
import {useParams,  useNavigate } from 'react-router-dom';
import { useFetchFollowList, userPrivateClick,useFetchFollowerList,userFollowClick, useFecthPrivate } from '../Component/api/userHooks';
import { useFetchUser } from '../Component/api/user';
import LinkiconHome from '../images/tegakihome.png';
import LinkiconBell from '../images/tegakibell.png';
import LinkiconMassage from '../images/tegakimessage.png';
import defaultAvatar from '../images/tegakicreateuser.png'
import Linkiconbutton from '../images/tegakibutton.png';
import LinkiconLogin from '../images/tegakilogin.png';
import LinkiconCreateUser from '../images/tegakicreateuser.png';
import LinkiconLogout from '../images/tegakilogout.png';
import LinkiconApp from '../images/tegakiappicon.png';
import Linkview from '../Component/LinkView/Linkview';
import Header from '../Component/Common/Header';
import AuthContext from '../Utils/AuthProvider';
import Logout from '../Component/Common/Logout';
import Search from '../Component/Search';
import Profileinfo from '../Component/ContentInfo/Profileinfo';
import UserPostsView from '../Component/Posts/UserPostsView';
import './css/UserprofileView.css'; 
import { mutate } from 'swr';
import { useFetchData } from '../Component/api/useFetchData';

function UserProfileView() {
    const { userid } = useParams(); // URLからユーザーIDを取得
    const { user } = useContext(AuthContext);
    const [userData, setUserData] = useState({}); // 指定ユーザーのデータを保存する状態
    const [searchKeyword, setSearchKeyword] = useState('');
    const navigate = useNavigate();
    const [followusers, setfollowUsers] = useState([]);
    const [followerusers, setfollowerUsers] = useState([]);
    const [isFollowed, setIsFollowed] = useState(false); // フォロー状態を管理
    const [isPrivated, setIsPrivated] = useState(false); // 鍵垢状態を管理
    const {data:privateClickData,error:privateClickerror} = useFecthPrivate(`http://localhost:8080/users/${userid}`);
    const {data:followListData,error:followListerror} = useFetchFollowList(`http://localhost:8080/users/${userid}/followed`);
    const {data:followerListData,error:followerListerror} = useFetchFollowerList(`http://localhost:8080/users/${userid}/follower`);
    const {data,error} = useFetchData(`http://localhost:8080/users/${userid}`);
   
    const handleUserIconClick = () => {
      navigate(`/users/${userid}/profile`)
    };

    const handleUserPrivateClick = async () => {
      try {
        if(privateClickerror){
          throw new Error('ユーザーデータの取得に失敗しました');
        }
        // プライベート設定のトグル操作
        const url = `http://localhost:8080/users/${user.id}/private`;
        const response = await userPrivateClick(url);
    
        if (!response) {
          throw new Error('プライベート設定の更新に失敗しました');
        }

        // プライベート状態の取得
        const isPrivate = privateClickData?.data?.is_private; // `is_private` プロパティを直接確認
        await mutate(`http://localhost:8080/users/${userid}`);
        setIsPrivated(isPrivate); // 状態を更新
      } catch (error) {
        console.error('Error handling user private click:', error);
      }
    };
    

    const handleFollowListClick = (clickedUser) => {
      if (Number(clickedUser.id) === Number(user.id)) {
        navigate("/Mypage"); // ログイン中のユーザーならマイページに遷移
      } else {
        navigate(`/users/${clickedUser.id}/profile`); // 他のユーザーならプロフィールページへ遷移
      }
    };

    const fetchprivate = async () => {
      try {
        if(privateClickerror){
          throw new Error('ユーザーデータの取得に失敗しました');
        }

        const isPrivate = privateClickData?.data?.is_private; // `is_private` プロパティを直接確認
        setIsPrivated(isPrivate); // 状態を更新
      } catch (error) {
        console.error('Error handling user private click:', error);
      }
    };

    const fetchFollowList = async () => {
      try {
        if(followListerror){
          throw new Error('フォローリストの取得に失敗しました');
        }
        setfollowUsers(Array.isArray(followListData) ? followListData : []); // フォローリストを保存
      } catch (error) {
        console.error('Error fetching follow list:', error);
      }
    };
    
    const fetchFollowerList = async () => {
      try {
        if(followerListerror){
          throw new Error('フォロワーリストの取得に失敗しました');
        }
        console.log(followerListData);
        setfollowerUsers(Array.isArray(followerListData) ? followerListData :  []); // フォロワーリストを保存
      } catch (error) {
        console.error('Error fetching follow list:', error);
      }
    };

    const handleUserFollowClick = async () => {
      try {
        const url = `http://localhost:8080/users/${user.id}/follow/${userid}`;
        const response = await userFollowClick(url,isFollowed);
  
        if (!response) {
          throw new Error('認証に失敗しました');
        }
        if(Array.isArray(followerListData)){
          const isAlreadyFollowed = followerListData.some(followerUser => followerUser.id === Number(user.id));
          setIsFollowed(isAlreadyFollowed);
        }else setIsFollowed(false);
  
      } catch (error) {

      }
    };

    useEffect(() => {
        const fetchUserData = async () => {
          try {
            if(data){
              setUserData(data); 
            }
            if(Array.isArray(followerListData)){
              const isAlreadyFollowed = followerListData.some(followerUser => followerUser.id === Number(user.id));
              setIsFollowed(isAlreadyFollowed);
            }else setIsFollowed(false);
          } catch (error) {
            console.error('Error fetching user data:', error);
            
          }
        };
        fetchFollowList();
        fetchUserData();
        fetchFollowerList();
        fetchprivate();
      }, [userid,followListData,followerListData]);

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
    }
  ];

  if (!user?.id) {
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
      },
      {
        src: (user?.avatar_path ? `http://localhost:8080${user.avatar_path}` : LinkiconCreateUser), // 更新されたアイコンを表示
        alt: 'Linkicon1',
        to: '/Mypage',
        text: "マイページ"
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
                onUserIconClick={handleUserIconClick}
                src={userData.avatar_path ? `http://localhost:8080${userData.avatar_path}` : defaultAvatar}
                username={userData.username}
                userid={userData.id}
                profile={userData.profile}
                onUserFollowClick={handleUserFollowClick}
                isFollowed={isFollowed}
                users={followusers}
                onFollowListClick={handleFollowListClick}
                followerusers={followerusers}
                onFollowerListClick={handleFollowListClick}
                onUserPrivateClick={handleUserPrivateClick}
                isPrivated={isPrivated}
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
