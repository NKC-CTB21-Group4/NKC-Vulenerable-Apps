import React, { useContext, useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom'; // useNavigate をインポート
import { useFetchPosts, usesearchPost } from '../api/post';
import './css/MyPostView.css'; // CSSファイルをインポート
import Contentinfo from '../ContentInfo/Contentinfo';
import AuthContext from '../../Utils/AuthProvider';
import defaultAvatar from '../../images/tegakicreateuser.png'

function MyPostView({ render}) { // デフォルト値として空の配列を設定
  const [posts, setPosts] = useState([]); 
  const { user } = useContext(AuthContext);
  const userid = user?.id;
  const { data,error,mutate} = useFetchPosts(`http://localhost:8080/users/${userid}/posts`);
  const navigate = useNavigate();

  useEffect(() => {
    // 初回読み込み時にユーザーの投稿を取得する関数
    if(data && posts.length ===0){
      if (!userid) return;
        const mypostarray = Object.values(data).reverse(); // 逆順にソート
        setPosts(mypostarray);
    }
  
    const handleNewPost = (event) => {
      setPosts((prevPosts) => [event.detail, ...prevPosts]);
      mutate(`http://localhost:8080/users/${userid}/posts`);
    };

    window.addEventListener('newPost', handleNewPost);


    // カスタムイベントをリッスンして検索結果を取得する関数
    const handleSearchEvent = async (event) => {
      const { keyword, authorName, dateFrom, dateTo } = event.detail;

      // 検索パラメータをクエリストリングとして生成
      const queryParams = new URLSearchParams({
        keyword,
        authorId:userid,
        authorName,
        dateFrom,
        dateTo
      });

      try {
        // 検索APIにリクエスト
        const url = `http://localhost:8080/posts/search?${queryParams.toString()}`;
        const response = await usesearchPost(url);
        //ポストの配列が返された場合のみpostsにset
        if(response){
          setPosts(response);
        }
      } catch (error) {
        console.error('Error fetching search results:', error);
      }
    };

    // 検索イベントをリッスン
    window.addEventListener('SearchPost', handleSearchEvent);

    // クリーンアップ：コンポーネントのアンマウント時にイベントリスナーを削除
    return () => {
      window.removeEventListener('SearchPost', handleSearchEvent);
      window.removeEventListener('newPost', handleNewPost);
    };
  },[render,data]);

  const handleUserIconClick = () => {
    navigate("/Mypage")
  };


  // 投稿削除時にリストを更新する関数
  const handleDelete = (postid) => {
    setPosts(posts.filter((post) => post.id !== postid));
  };

  return (
    <div className="mypostview-container">
      {posts.length > 0 ? (
        posts.map((post) => (
          <Contentinfo
            key={post.id}
            src={post.author_avatar ? `http://localhost:8080${post.author_avatar}` : defaultAvatar} 
            alt=""
            username={post.author_name}
            userid={post.author_id}
            content={post.content}
            postid={post.id}
            imagepath={post.image_path}
            handleDelete={handleDelete} // handleDelete関数のプロップス名を修正
            onUserIconClick={handleUserIconClick}
          />
        ))
      ) : (
        <p className='MyPostView-message'>表示する投稿がありません。</p>
      )}
    </div>
  );
}

export default MyPostView;
