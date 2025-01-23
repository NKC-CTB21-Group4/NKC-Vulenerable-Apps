import React, { useEffect, useState,useContext } from 'react';
import { useFetchFavorites,clickFavorites } from '../api/post';
import './css/Fav.css';
import AuthContext from '../../Utils/AuthProvider';

function Fav({ postid }) {
    const [favorites, setFavorites] = useState({fav: 0, clicked:false});
    const [error, setError] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const { user } = useContext(AuthContext);
    const userid = user.id;
    const {data, error:fetchFaverror, mutate} = useFetchFavorites(postid ? `http://localhost:8080/favorite/posts/${postid}` : null);

    useEffect(() => {
        if(data){
            setFavorites(data);
        }
        if(fetchFaverror){
            setError('お気に入り情報を取得できませんでした。');
        }
        mutate();
    }, [data]);

    const handleFavoriteClick = async () => {
        if(!userid){
            return;
        }
        setError(null);
        setIsLoading(true);
        try {
            const response = await clickFavorites(`http://localhost:8080/favorite/posts/${postid}`);
            setFavorites({
                fav: response.data ? favorites.fav + 1 : favorites.fav - 1,
                clicked: response.data
            });
            mutate();
        } catch (err) {
            setError('Failed to update favorites');
        }
        setIsLoading(false);
    };

    return (
        <div className='fav-container'>
            <span 
                onClick={!isLoading ? handleFavoriteClick : null} 
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
