import useSWR from "swr";
import { mutate, useSWRConfig } from 'swr/_internal';
import { useFetchData } from "./useFetchData";

// 認証トークンを取得する関数
const getAuthHeaders = () => {
  const token = localStorage.getItem('authToken');
  return token ? { 'Authorization': `Bearer ${token}` } : {};
};

// カスタムフックとして定義
export function useFetchUsers(apiEndpoint) {
  const { data, error, mutate } = useFetchData(apiEndpoint,"",true)
  const { cache } = useSWRConfig();

  return { data, error, mutate, cache };
}

export function useFetchMessage(apiEndpoint){
    const{data ,error, mutate } = useFetchData(apiEndpoint,"",true)
    const{ cache } = useSWRConfig();
    
    return {data, error, mutate, cache};
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

export async function searchUsers(apiEndpoint) {
    try{
        const response = await fetch(apiEndpoint,{
            method:'GET',
            headers:{
                'Content-Type': 'application/json',
                ...getAuthHeaders(),
            }
        });

        const responseData = await response.json();

        if(!response.ok){
          throw new Error(responseData.message || 'ユーザーの検索に失敗しました');
        }
        return responseData;
    }catch(error){
      console.error('エラーが発生しました:', error.message);
      throw error;
    }
}


export { mutate }