import useSWR from 'swr';
import {mutate, useSWRConfig} from 'swr/_internal';

const fetcher = url => fetch(url).then(res => res.json());

//ポスト取得
export function FetchPosts(apiEndpoint) {
  const { data, error } = useSWR(apiEndpoint, fetcher);
  const { cache } = useSWRConfig();

  if (error) return <div>Failed to load</div>;
  if (!data) return <div>Loading...</div>;

  return {data:data, error, mutate,cache};
}

//ポスト作成
export async function CreatePosts(apiEndpoint, formData) {
  try {
    const response = await fetch(apiEndpoint, {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
      },
      body: formData,
    });

    const responseData = await response.json();

    if (!response.ok) {
      throw new Error(responseData.message || '投稿の作成に失敗しました');
    }

    return responseData;
  } catch (error) {
    console.error('エラーが発生しました:', error.message);
    throw error;
  }
}


//いいね数取得
export function useFetchFavs(apiEndpoint) {
  const token = localStorage.getItem('authToken');

  // データ取得関数 (fetcher)
  const fetcher = async (url) => {
    const response = await fetch(url, {
      method: 'GET',
      headers: {
        'Authorization': 'Bearer ' + token,
      },
    });

    const responseData = await response.json();

    if (!response.ok) {
      throw new Error(responseData.message || 'いいねの取得に失敗しました');
    }

    return responseData;
  };

  // useSWR フックの使用
  const { data, error, mutate } = useSWR(apiEndpoint, fetcher);

  return {
    data:data,
    error,
    mutate,
  };
}

//いいねクリック
export async function ClickFavorites(apiEndpoint) {
  try {
    const response = await fetch(apiEndpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
      },
    });

    const responseData = await response.json();

    if (!response.ok) {
      throw new Error(responseData.message || '投稿の作成に失敗しました');
    }

    return responseData;
  } catch (error) {
    console.error('エラーが発生しました:', error.message);
    throw error;
  }
}

//ポスト削除
export async function DeletePost(postId,userId) {
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

export { mutate };

