import React, { useContext } from 'react';
import AuthContext from '../Utils/AuthProvider';
import './Mainview.css'; // CSSファイルをインポート
import Linkview from './Linkview';
import PostView from './PostView';
import Header from './Header';
import LinkiconHome from '../images/tegakihome.png';
import LinkiconBell from '../images/tegakibell.png';
import LinkiconMassage from '../images/tegakimessage.png';
import Iconhavertz from '../images/havertz.png';
import Linkiconbutton from '../images/tegakibutton.png';

function Mainview() {

const {user} = useContext(AuthContext);
const userid = user.id;

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
    to: '/mypage',
    text: "通報"
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
];

  return (
    <div className="mainview-container">
      <Linkview links={links} />
    <div className="header-posts-container">
      <Header/>
      <PostView/>
    </div>
    </div>
  );
}

export default Mainview;
