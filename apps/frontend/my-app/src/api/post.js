import useSWR from 'swr';
import {mutate} from 'swr/_internal';

const fetcher = url => fetch(url).then(res => res.json());

export function FetchPosts(apiEndpoint) {
  const { data, error } = useSWR(apiEndpoint, fetcher);
  console.log(data);

  if (error) return <div>Failed to load</div>;
  if (!data) return <div>Loading...</div>;

  return {data:data, error, mutate};
}

export { mutate };