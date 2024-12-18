import { BrowserRouter as Router, Route, Routes} from 'react-router-dom';
import './App.css';
import AdminView from './View/AdminView';
import { AuthProvider } from './Utils/AuthProvider';
import Login from './View/Login';
import Mainview from './View/Mainview';
import CreateUser from './View/CreateUser';
import Mypage from './View/MyPage';
import Logout from './Component/Common/Logout';
import DirectMessage from './Component/DirectMessage/DirectMessage';
import UserProfileView from './View/UserprofileView';


function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          <Route path="/" element={<Mainview/>}/>
          <Route path="/login" element={<Login />}/>
          <Route path="/logout" element={<Logout />}/>
          <Route path="/signup" element={<CreateUser/>}/>
          <Route path="/admin/*" element={<AdminView />} />
          <Route path="/mypage" element={<Mypage/>}/>
          <Route path="/dm" element={<DirectMessage/>}/>
          <Route path="/users/:userid/profile" element={<UserProfileView />} /> {/* OthersPage へのルート */}
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
