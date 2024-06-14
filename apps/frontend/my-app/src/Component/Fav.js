import React, { useEffect, useState } from 'react';

function Fav({ postid }) {
    const [favorites, setFavorites] = useState({});
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchFavorites = async () => {
            try {
                // Simulate fetching favorites from a server
                //const response = await fetch(`http://localhost:8080/api/favorite/posts/${postid}`);
                const response = await fetch("http://localhost:8081/favdata/fav.php");
                const json = await response.json();
                setFavorites(json.data);
            } catch (err) {
                setError('Failed to fetch favorites');
            }
        };
        fetchFavorites();
    }, []);

    const handleFavoriteClick = async () => {
        setError(null);
        try {
            /*
            // Simulate an async API call to update favorites
            const response = await fetch(`http://localhost:8080/api/favorite/posts/${postid}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            */
            const response = await fetch("http://localhost:8081/favdata/fav.php");
            const json = await response.json();
            console.log(json.data);
            setFavorites({fav:json.data.clicked ? favorites.fav + 1 : favorites.fav - 1, clicked:json.data.clicked});
        } catch (err) {
            setError('Failed to update favorites');
        }
    };

    return (
        <div>
            <span onClick={handleFavoriteClick} 
                style={{cursor: 'pointer', 
                        color: favorites.clicked ? 'red' : 'white',
                        
                }}
            >
                ♥
            </span>
            <span style={{color: 'black'}}>{favorites.fav}</span>
            {error && <span>{error}</span>}
        </div>
    );
}

export default Fav;
