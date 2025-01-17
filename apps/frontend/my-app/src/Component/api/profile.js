import useSWR,{mutate,useSWRConfig} from 'swr';

// 認証トークンを取得する関数
const getAuthHeaders = () => {
  const token = localStorage.getItem('authToken');
  return token ? { 'Authorization': `Bearer ${token}` } : {};
};

// 共通のfetcher関数
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

export async function uploadImage(apiEndpoint,formData) {
    try{
        const response = await fetch(apiEndpoint,{
            method: 'POST',
            headers: {
              ...getAuthHeaders(),
            },
            body: formData,
          }
        )

        const result = await response.json();

        if(!response.ok){
            throw new Error(result.message || 'プロフィールの更新に失敗しました');
        }
        return result;
    }catch(error){
        console.error("エラーが発生しました",error.message);
        throw error;
    }
}

export async function updateUserProfile(apiEndpoint,username,profile) {
    try{
      const response = await fetch(apiEndpoint, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          ...getAuthHeaders(),
        },
        body: JSON.stringify({ username,profile }),
         // ユーザー名とプロフィール文を送信
      });
      const json = (await response.json()).data;

      if(!response.ok){
        throw new Error(json.message || 'ユーザーの検索に失敗しました');
      }

      return json;
    }catch(error){
      console.error('エラーが発生しました:', error.message);
      throw error;
    }
}