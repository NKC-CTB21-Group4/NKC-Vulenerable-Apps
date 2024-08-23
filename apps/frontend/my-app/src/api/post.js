import useSWR from 'swr';
import { mutate, useSWRConfig } from 'swr/_internal';

// 認証トークンを取得する関数
const getAuthHeaders = () => {
  const token = localStorage.getItem('authToken');
  return token ? { 'Authorization': `Bearer ${token}` } : {};
};

// 共通のfetcher関数
const fetcher = async (url, options = { needsAuth: false, headers: {} }) => {
  const headers = {
    ...options.headers, // 追加のカスタムヘッダーがあればここにマージ
    ...(options.needsAuth ? getAuthHeaders() : {}), // 認証が必要なら認証ヘッダーを追加
  };

  const response = await fetch(url, { method: 'GET', headers });
  const responseData = await response.json();

  if (!response.ok) {
    throw new Error(responseData.message || 'データの取得に失敗しました');
  }

  return responseData;
};

// ポスト取得
export function useFetchPosts(apiEndpoint) {
  const { data, error } = useSWR(apiEndpoint, (url) => fetcher(url));
  const { cache } = useSWRConfig();

  return { data, error, mutate, cache };
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

// いいね数取得
export function useFetchFavorites(apiEndpoint) {
  const { data, error, mutate } = useSWR(apiEndpoint, (url) => fetcher(url, { needsAuth: true }));

  return { data, error, mutate };
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
export async function deletePost(postId, userId) {
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

export { mutate };
