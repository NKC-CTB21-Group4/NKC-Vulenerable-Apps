import useSWR from 'swr';
import {mutate} from 'swr/_internal';

const fetcher = url => fetch(url).then(res => res.json());

export function FetchPosts(apiEndpoint) {
  const { data, error } = useSWR(apiEndpoint, fetcher);

  if (error) return <div>Failed to load</div>;
  if (!data) return <div>Loading...</div>;

  return {data:data, error, mutate};
}

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


export { mutate };