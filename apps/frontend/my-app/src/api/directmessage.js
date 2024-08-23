import useSWR from "swr";
import { mutate, useSWRConfig } from 'swr/_internal';

// 認証トークンを取得する関数
const getAuthHeaders = () => {
  const token = localStorage.getItem('authToken');
  return token ? { 'Authorization': `Bearer ${token}` } : {};
};

// 柔軟な fetcher を定義
const fetcher = async (url, options = { needsAuth: false, headers: {} }) => {
  const headers = {
    ...options.headers, // 追加のカスタムヘッダーがあればここにマージ
    ...(options.needsAuth ? getAuthHeaders() : {}), // 認証が必要なら認証ヘッダーを追加
  };

  const response = await fetch(url, {method: 'GET',headers,});
  const responseData = await response.json();

  if (!response.ok) {
    throw new Error(responseData.message || 'データの取得に失敗しました。');
  }

  return responseData;
};

// カスタムフックとして定義
export function useFetchUsers(apiEndpoint) {
  const { data, error, mutate } = useSWR(apiEndpoint,(url) => fetcher(url, { needsAuth:true }));
  const { cache } = useSWRConfig();

  return { data:data, error, mutate, cache };
}

export function useFetchMessage(apiEndpoint){
    const{data ,error, mutate } = useSWR(apiEndpoint,(url) => fetcher(url, { needsAuth:true }));
    const{ cache } = useSWRConfig();
    
    return {data:data, error, mutate, cache};
}

export async function sendMessage(apiEndpoint,message) {
    try {
        const response = await fetch(apiEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            ...getAuthHeaders(),
          },
          body: JSON.stringify({ message }),
        });
    
        const responseData = await response.json();
    
        if (!response.ok) {
          throw new Error(responseData.message || 'メッセージの送信に失敗しました。');
        }
    
        return responseData;
      } catch (error) {
        console.error('エラーが発生しました:', error.message);
        throw error;
      }
}


export { mutate }