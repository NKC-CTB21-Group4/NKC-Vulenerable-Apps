import useSWR,{mutate,useSWRConfig} from 'swr';
import { useFetchData } from './useFetchData';

// 認証トークンを取得する関数
const getAuthHeaders = () => {
  const token = localStorage.getItem('authToken');
  return token ? { 'Authorization': `Bearer ${token}` } : {};
};

// ポスト取得
export function useFetchPosts(apiEndpoint,caller,needsAuth) {
  const { data, error, mutate } = useFetchData(apiEndpoint,caller,needsAuth)

  return { data, error, mutate};
}

// いいね数取得
export function useFetchFavorites(apiEndpoint) {
  const { data, error, mutate } = useFetchData(apiEndpoint,"",true)

  return { data, error, mutate };
}

// ポスト作成
export async function createPost(apiEndpoint, formData) {
  try {
    const response = await fetch(apiEndpoint, {
      method: 'POST',
      headers: {
        ...getAuthHeaders(),
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

// いいねクリック
export async function clickFavorites(apiEndpoint) {
  try {
    const response = await fetch(apiEndpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        ...getAuthHeaders(),
      },
    });

    const responseData = await response.json();

    if (!response.ok) {
      throw new Error(responseData.message || 'いいねの操作に失敗しました');
    }

    return responseData;
  } catch (error) {
    console.error('エラーが発生しました:', error.message);
    throw error;
  }
}

// ポスト削除
export async function deletePosts(postId, userId) {
  try {
    const response = await fetch(`http://localhost:8080/users/${userId}/posts/${postId}`, {
      method: 'DELETE',
      headers: {
        ...getAuthHeaders(),
      },
    });

    if (!response.ok) {
      throw new Error('ポストの削除に失敗しました');
    }
  } catch (error) {
    console.error('Error:', error);
    throw error;
  }
}

//ポスト検索
export async function searchPosts(apiEndpoint) {
    try {
        // 検索APIにリクエスト
        const response = await fetch(apiEndpoint, {
          headers: {
            ...getAuthHeaders(),
          }
        });
        const json = await response.json();
        const searchpostarray = Object.values(json.data)
        .reverse()  // 逆順にソート
        .filter((post) => post.deleted_at === null); // deleted_at が null の場合のみ
        return searchpostarray;
      } catch (error) {
        console.error('Error fetching search results:', error);
      }
}

export { mutate };