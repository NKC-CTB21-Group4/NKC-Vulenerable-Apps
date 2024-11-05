function deletePost(postId,userId) {
  const authtoken = localStorage.getItem('authToken');
    return fetch(`http://localhost:8080/users/${userId}/posts/${postId}`, {
      method: 'DELETE',
      headers:{
        'Authorization': `Bearer ${authtoken}`,
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error("ポストの削除に失敗")
      }
    })
    .catch(error => {
      console.error('Error:', error);
      throw new Error(error)
    });
  
}

export default deletePost;
