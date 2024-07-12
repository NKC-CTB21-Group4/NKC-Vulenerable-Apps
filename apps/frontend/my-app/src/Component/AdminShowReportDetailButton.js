import React, {} from 'react';
import { Button } from '@mui/material';
import {useNavigate} from 'react-router-dom';


const AdminShowReportDetailButton = ({ postId }) => {
  const navigate = useNavigate();

  const handleClick = async () => {
    navigate(`/admin/posts/${postId}/reports`);
  }
  return (
    <div>
      <Button variant="contained" color={"primary"} onClick={handleClick}>
        通報一覧
      </Button>
    </div>
  );
};

export default AdminShowReportDetailButton;
