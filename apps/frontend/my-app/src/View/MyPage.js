import React,{useContext,useEffect} from 'react';
import './MyPage.css'; // CSSファイルをインポート
import LinkiconHome from '../images/tegakihome.png';
import LinkiconSerch from '../images/tegakiserch.png';
import LinkiconBell from '../images/tegakibell.png';
import LinkiconMassage from '../images/tegakimessage.png';
import Iconhavertz from '../images/havertz.png';
import Linkiconbutton from '../images/tegakibutton.png';
import MyPostview from '../Component/MyPostView';
import Linkview from '../Component/Linkview';
import AuthContext from '../Utils/AuthProvider';
import {useNavigate} from 'react-router-dom';
import Header from '../Component/Header';

function MyPage() {
  const { user,isAuthenticated } = useContext(AuthContext);
  const userid = user?.id;
  const navigate = useNavigate();

  useEffect(() => {
    if (!isAuthenticated) {
      navigate('/mypage');
    }
  }, [isAuthenticated, navigate]);

  useEffect(() => {
    if(!userid)return;
  }, [userid]);

  const links = [
    {
      src: LinkiconHome,
      alt: 'User Icon',
      to: '/',
      text: "ホーム"
    },
    {
      src: Linkiconbutton,
      alt: 'Linkicon1',
      to: 'Mypage',
      text: "通報"
    },
    {
      src: LinkiconSerch,
      alt: 'Linkicon3',
      to: 'Mypage',
      text: "検索"
    },
    {
      src: LinkiconBell,
      alt: 'Linkicon4',
      to: 'Mypage',
      text: "通知"
    },
    {
      src: LinkiconMassage,
      alt: 'Linkicon1',
      to: 'Mypage',
      text: "メッセージ"
    },
    {
      src: userid ? `http://localhost:8080/users/${userid}/avatar` : Iconhavertz,
      alt: 'Linkicon1',
      to: 'Mypage',
      text: "マイページ"
    }
  ]

  return (
    <header className='mypage-App-header'>
      <div className="mypage-container">
      <Linkview links={links} />
    <div className="mypage-header-posts-container">
      <Header/>
      <MyPostview/>
    </div>
    </div>
    </header>
  );
}

export default MyPage;
