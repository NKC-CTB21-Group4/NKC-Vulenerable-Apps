import useSWR,{mutate,useSWRConfig} from 'swr';
import { useFetchData } from './useFetchData';

const getAuthHeaders = () => {
    const token = localStorage.getItem('authToken');
    return token ? { 'Authorization': `Bearer ${token}` } : {};
  };

export async function useFecthPrivate(apiEndpoint) {
const {data,error,mutate} = useFetchData(apiEndpoint,"",true);

return {data,error,mutate};
}

export function useFetchFollowList(apiEndpoint) {
const {data,error,mutate} = useFetchData(apiEndpoint,"",true);

return {data,error,mutate};
}

export function useFetchFollowerList(apiEndpoint) {
const {data,error,mutate} = useFetchData(apiEndpoint,"",true);

return {data,error,mutate};
}

export async function userPrivateClick(apiEndpoint) {
    try{
      const response = await fetch(apiEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          ...getAuthHeaders(),
        },
      });

      if(!response){
        throw new Error('プライベート設定の更新に失敗しました');
      }

    }catch(error){
      console.error('エラーが発生しました。',error.message);
      throw error;
    }
  }
  export async function userFollowClick(apiEndpoint,isFollowed) {
    try{
        const response = await fetch(apiEndpoint, {
            method: isFollowed ? 'DELETE' : 'POST',
            headers: {
              'Content-Type': 'application/json',
              ...getAuthHeaders(),
            },
        });

        if(!response){
            throw new Error('ユーザーのフォローに失敗しました');
        }
    }catch(error){
        console.error('Error fetching follow list:', error);
    }
  }
