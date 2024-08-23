import useSWR from "swr";
import { mutate,useSWRConfig } from 'swr/_internal';

const getAuthHeaders = () => {
    const token = localStorage.getItem('authToken');
    return token ? { 'Authorization': `Bearer ${token}` } : {};
  };

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
  

  export function useFetchTags(apiEndpoint){
    const { data, error } = useSWR(apiEndpoint, (url) => fetcher(url));
    const { cache } = useSWRConfig();

  return { data:data, error, mutate, cache };
  }

  export async function reportPosts(apiEndpoint,selectedIds,additionalInfo) {
    try {
        const response = await fetch(apiEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            ...getAuthHeaders(),
          },
          body: JSON.stringify({
            tag_ids: selectedIds,
            reason: additionalInfo // テキストエリアの内容も送信
          }),
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