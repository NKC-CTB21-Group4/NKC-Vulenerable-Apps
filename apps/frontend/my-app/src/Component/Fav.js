import React, { useEffect, useState } from 'react';

function Fav({ postid }) {
    const [favorites, setFavorites] = useState({});
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchFavorites = async () => {
            try {
                const authtoken = localStorage.getItem('authToken');
                const response = await fetch(`http://localhost:8080/favorite/posts/${postid}`, {
                    metho : 'GET',
                    headers: {
                        'Authorization': `Bearer ${authtoken}`,
                    },
                });
                const json = await response.json();
                setFavorites(json.data);
            } catch (err) {
                setError('Failed to fetch favorites');
            }
        };
        fetchFavorites();
    }, [postid]);

    const handleFavoriteClick = async () => {
        setError(null);
        try {
            const authtoken = localStorage.getItem('authToken');
            const response = await fetch(`http://localhost:8080/favorite/posts/${postid}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authtoken}`,
                },
            });
            const json = await response.json();
            setFavorites({
                fav: json.data ? favorites.fav + 1 : favorites.fav - 1,
                clicked: json.data
            });
        } catch (err) {
            setError('Failed to update favorites');
        }
    };

    return (
        <div>
            <span 
                onClick={handleFavoriteClick} 
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
