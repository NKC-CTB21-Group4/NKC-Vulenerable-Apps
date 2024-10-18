import React,{useContext,useEffect,useState} from 'react';
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
import Header from '../Component/Header';
import AuthContext from '../Utils/AuthProvider';
import Logout from '../Component/Logout';
import Search from '../Component/Search';

function MyPage({}) {
  const { user,isAuthenticated } = useContext(AuthContext);
  const [searchKeyword, setSearchKeyword] = useState('');
  const userid = user?.id;
  const username = user?.username;
  const navigate = useNavigate();
  console.log('user:', user);

  useEffect(() => {
    if (!isAuthenticated) {
      navigate('/');
    }
  }, [isAuthenticated, navigate]);

  useEffect(() => {
    if(!userid)return;
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
      src: userid ? `http://localhost:8080/users/${userid}/avatar` : Iconhavertz,
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
      <Header/>
      <MyPostview searchKeyword={searchKeyword}/>
    </div>
    <Search setSearchKeyword={setSearchKeyword} searchKeyword={searchKeyword} />
    </div>
    <Logout />
    </header>
  );
}

export default MyPage;
