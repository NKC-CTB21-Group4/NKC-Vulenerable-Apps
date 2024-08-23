import React, { useState, useContext,useEffect } from 'react';
import './css/Fav.css';
import AuthContext from '../../Utils/AuthProvider';
import { useFetchFavorites,clickFavorites } from '../../api/post';

function Fav({ postid }) {
  const [favorites, setFavorites] = useState({ fav: 0, clicked: false });
  const [error, setError] = useState(null);
  const [isLoading, setisLoading] = useState(false); // isLoading を loadingState に置き換え
  const { user } = useContext(AuthContext);
  const userid = user.id;

  // fetchFavs 関数を使用してお気に入りデータを取得
  const { data, error: fetchError, mutate } = useFetchFavorites(
    postid ? `http://localhost:8080/favorite/posts/${postid}` : null
  );

  // データが取得できた場合、状態を更新
  useEffect(() => {
    if (data) {
      setFavorites(data.data);
    }
    if (fetchError) {
      setError('お気に入り情報を取得できませんでした。');
    }
  }, [data, fetchError]);

  const handleFavoriteClick = async () => {
    if (!userid) {
      return;
    }
    setError(null);
    setisLoading(true); // ローディング開始
    try {
        const responseData  = await clickFavorites(`http://localhost:8080/favorite/posts/${postid}`);
      setFavorites({
        fav: responseData.data ? favorites.fav + 1 : favorites.fav - 1,
        clicked: responseData.data,
      });
      mutate();
    } catch (err) {
      setError('お気に入りの更新に失敗しました。');
    } finally {
      setisLoading(false); // ローディング終了
    }
  };
  if (fetchError) return <p>エラーが発生しました: {fetchError.message}</p>; // エラー表示

  return (
    <div className='fav-container'>
      <span
        onClick={!isLoading ? handleFavoriteClick : null} // ローディング中はクリック不可
        style={{
          cursor: 'pointer',
          color: favorites.clicked ? 'red' : 'black',
        }}
      >
        ♥
      </span>
      <span style={{ color: 'black' }}>{favorites.fav}</span>
      {error && <span>{error}</span>}
    </div>
  );
}

export default Fav;
