import useSWR from "swr";
import { mutate,useSWRConfig } from 'swr/_internal';
import { useFetchData } from "./useFetchData";

const getAuthHeaders = () => {
    const token = localStorage.getItem('authToken');
    return token ? { 'Authorization': `Bearer ${token}` } : {};
  };

  export function useFetchTags(apiEndpoint){
    const { data, error } = useFetchData(apiEndpoint,"",true)
    const { cache } = useSWRConfig();

  return { data, error, mutate, cache };
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