import { BrowserRouter as Router, Route, Routes,Navigate } from 'react-router-dom';
import './App.css';
import AdminView from './View/AdminView';
import { AuthProvider } from './Utils/AuthProvider';
import Login from './Component/Login';
import Mainview from './Component/Mainview';


function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          <Route path="/" element={<Mainview/>}/>
          <Route path="/login" element={<Login />}/>
          <Route path="/admin/*" element={<AdminView />} />
          <Route path="/*" element={<Navigate to="/" />}/>
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
