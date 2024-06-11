import React, { useEffect, useState } from 'react';

function Fav({ postid }) {
    const [favorites, setFavorites] = useState({});
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchFavorites = async () => {
            try {
                // Simulate fetching favorites from a server
                const response = await fetch(`http://backend:8080/api/favorite/posts/${postid}`);
                const json = await response.json();
                setFavorites(json.data);
                //json.data.cickedを判定しクリックされていたらいいね数のアイコンを変える。
            } catch (err) {
                setError('Failed to fetch favorites');
            } finally {
                setLoading(false);
            }
        };
        fetchFavorites();
    }, []);

    const handleFavoriteClick = async () => {
        setError(null);
        try {
            // Simulate an async API call to update favorites
            const response = await fetch(`http://backend:8080/api/favorite/posts/${postid}`, {
                method: 'POST',
            });
            const json = await response.json();
            setFavorites({fav:json.data.clicked ? favorites.fav + 1 : favorites.fav - 1, clicked:json.data.clicked});
        } catch (err) {
            setError('Failed to update favorites');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <span onClick={handleFavoriteClick} 
                style={{cursor: 'pointer', 
                        color: favorites.clicked ? 'red' : 'white',
                }}
            >
                ❤️
            </span>
            <span>{favorites.fav}</span>
            {error && <span>{error}</span>}
        </div>
    );
}

export default Fav;
