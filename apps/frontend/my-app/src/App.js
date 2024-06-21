import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import './App.css';
import AdminView from './View/AdminView';
import { AuthProvider } from './Utils/AuthProvider';
import Login from './Component/Login';


function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          <Route path="/login" element={<Login />}/>
          <Route path="/admin/*" element={<AdminView />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
