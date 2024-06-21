import * as React  from 'react';
import { Routes, Route , Navigate} from 'react-router-dom';
import AdminPostList from '../Component/AdminPostList';
import AdminUserList from '../Component/AdminUserList';
import AdminHeader from '../Component/AdminHeader';
import { useContext } from 'react';
import AuthContext from '../Utils/AuthProvider';



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
        <Route path="/users" element={<AdminUserList/>} />
        <Route path="/posts" element={<AdminPostList/>} />
        <Route path="/*" element={<Navigate to="/admin/users" />} />
      </Routes>
    </div>
  );
}
