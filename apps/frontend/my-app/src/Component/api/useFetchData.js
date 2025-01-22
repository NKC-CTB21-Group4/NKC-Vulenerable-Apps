import useSWR,{mutate,useSWRConfig} from 'swr';

const getAuthHeaders = () => {
    const token = localStorage.getItem('authToken');
    return token ? { 'Authorization': `Bearer ${token}` } : {};
  };

const fetcher = async (url, caller,options = { needsAuth: false, headers: {} }) => {
    const headers = {
      ...options.headers, // 追加のカスタムヘッダーがあればここにマージ
      ...(options.needsAuth ? getAuthHeaders() : {}), // 認証が必要なら認証ヘッダーを追加
    };
  
    const response = await fetch(url, { method: 'GET', headers });
    const responseData = await response.json();
  
    if(caller === "UserPostsView.js"){
      return responseData;
    }
  
    if (!response.ok) {
      throw new Error(responseData.message || 'データの取得に失敗しました');
    }
  
    return responseData.data;
  };

  export function useFetchData(apiEndpoint, caller, needsAuth) {
    const { data, error } = useSWR(apiEndpoint, (url) => fetcher(url, caller,{ needsAuth:needsAuth }));
    const { cache, mutate } = useSWRConfig();
  
    return { data, error, mutate, cache };
  }