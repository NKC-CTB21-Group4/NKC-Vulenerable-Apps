import * as React  from 'react';
import { Routes, Route , Navigate} from 'react-router-dom';
import AdminPostList from '../Component/Admin/AdminPostList';
import AdminUserList from '../Component/Admin/AdminUserList';
import AdminHeader from '../Component/Admin/AdminHeader';
import AdminReportList from '../Component/Admin/AdminReportList';
import AdminReportDetailList from '../Component/Admin/AdminReportDetailList';
import { useContext } from 'react';
import AuthContext from '../Utils/AuthProvider';
import CreateAdminUser from './CreateAdminUser';



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
        <Route path="/posts/:id/reports" element={<AdminReportDetailList />} />
        <Route path="/posts" element={<AdminPostList/>} />
        <Route path="/reports" element={<AdminReportList/>}/>
        <Route path="/signup" element={<CreateAdminUser/>}/>
        <Route path="/*" element={<Navigate to="/admin/users" />} />
      </Routes>
    </div>
  );
}
