import React, { useEffect, useState } from 'react';

function Fav({ postid }) {
    const [favorites, setFavorites] = useState(0);
    const [state,setState] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchFavorites = async () => {
            try {
                // Simulate fetching favorites from a server
                const response = await fetch(`http://backend:8080/api/favorite/posts/${postid}`);
                const json = await response.json();
                setFavorites(json.data);
            } catch (err) {
                setError('Failed to fetch favorites');
            } finally {
                setLoading(false);
            }
        };

        fetchFavorites();
    }, [postid]);

    const handleFavoriteClick = async () => {
        setLoading(true);
        setError(null);
        try {
            // Simulate an async API call to update favorites
            const response = await fetch(`http://backend:8080/api/favorite/posts/${postid}`, {
                method: 'POST',
            });
            const json = await response.json();
            setFavorites(json.fav);
        } catch (err) {
            setError('Failed to update favorites');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <span onClick={handleFavoriteClick} style={{ cursor: 'pointer' }}>
                ❤️
            </span>
            <span>{favorites}</span>
            {loading && <span>Loading...</span>}
            {error && <span>{error}</span>}
        </div>
    );
}

export default Fav;
