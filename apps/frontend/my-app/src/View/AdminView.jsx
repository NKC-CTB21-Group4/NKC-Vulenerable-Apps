import * as React  from 'react';
import { Routes, Route , Navigate} from 'react-router-dom';
import AdminPostList from '../Component/AdminPostList';
import AdminUserList from '../Component/AdminUserList';
import AdminHeader from '../Component/AdminHeader';
import { useContext } from 'react';
import AuthContext from '../Utils/AuthProvider';

const users = [
  { id: 1, username: 'jonsnow', email: 'jon.snow@example.com', isAdmin: false, createdAt: '2023-01-01' },
  { id: 2, username: 'cersei', email: 'cersei.lannister@example.com', isAdmin: true, createdAt: '2023-01-02' },
  { id: 3, username: 'jaime', email: 'jaime.lannister@example.com', isAdmin: false, createdAt: '2023-01-03' },
  { id: 4, username: 'arya', email: 'arya.stark@example.com', isAdmin: false, createdAt: '2023-01-04' },
  { id: 5, username: 'daenerys', email: 'daenerys.targaryen@example.com', isAdmin: true, createdAt: '2023-01-05' },
  { id: 6, username: 'melisandre', email: 'melisandre@example.com', isAdmin: false, createdAt: '2023-01-06' },
  { id: 7, username: 'clifford', email: 'clifford.ferrara@example.com', isAdmin: false, createdAt: '2023-01-07' },
  { id: 8, username: 'frances', email: 'frances.rossini@example.com', isAdmin: false, createdAt: '2023-01-08' },
  { id: 9, username: 'roxie', email: 'roxie.harvey@example.com', isAdmin: false, createdAt: '2023-01-09' },
];

const posts = [
  { id: 1, author_id: 100, author_name: 'John Doe', content: 'This is the first post.', createdAt: '2023-01-01' },
  { id: 2, author_id: 101, author_name: 'Jane Smith', content: 'This is the second post.', createdAt: '2023-01-02' },
  { id: 3, author_id: 102, author_name: 'Alice Johnson', content: 'This is the third post.', createdAt: '2023-01-03' },
  { id: 4, author_id: 103, author_name: 'Bob Brown', content: 'This is the fourth post.', createdAt: '2023-01-04' },
  { id: 5, author_id: 104, author_name: 'Charlie Davis', content: 'This is the fifth post.', createdAt: '2023-01-05' },
  { id: 6, author_id: 105, author_name: 'David Evans', content: 'This is the sixth post.', createdAt: '2023-01-06' },
  { id: 7, author_id: 106, author_name: 'Ella Fitzgerald', content: 'This is the seventh post.', createdAt: '2023-01-07' },
  { id: 8, author_id: 107, author_name: 'Frank Harris', content: 'This is the eighth post.', createdAt: '2023-01-08' },
  { id: 9, author_id: 108, author_name: 'Grace Kelly', content: 'This is the ninth post.', createdAt: '2023-01-09' },
  { id: 10, author_id: 109, author_name: 'Henry Lee', content: 'This is the tenth post.', createdAt: '2023-01-10' },
];


export default function AdminView() {

  const { isAdmin,user } = useContext(AuthContext);
  console.log(user)
  if (!isAdmin) {
    return <div>アクセスが拒否されました。ログインしてください。</div>;
  }

  return (
    <div>
      <AdminHeader />
      <Routes>
        <Route path="/users" element={<AdminUserList rows={users}/>} />
        <Route path="/posts" element={<AdminPostList rows={posts}/>} />
        <Route path="/*" element={<Navigate to="/admin/users" />} />
      </Routes>
    </div>
  );
}
