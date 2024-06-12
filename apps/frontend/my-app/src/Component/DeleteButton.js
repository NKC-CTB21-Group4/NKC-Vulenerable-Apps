import React, { useState } from 'react';

function DeleteButton({ userId, postId}) {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const handleDelete = async () => {
        setLoading(true);
        setError(null);

        try {
            const response = await fetch(`http://localhost:8080/users/${userId}/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            
            });

            if (!response.ok) {
                throw new Error('Failed to delete the post');
            }
        } catch (err) {
            setError('Failed to delete the post');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <button onClick={handleDelete} disabled={loading}>
                {loading ? 'Deleting...' : 'Delete Post'}
            </button>
            {error && <p style={{ color: 'red' }}>{error}</p>}
        </div>
    );
}

export default DeleteButton;
