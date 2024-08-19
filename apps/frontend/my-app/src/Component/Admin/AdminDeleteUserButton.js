import React, { useContext, useState } from 'react';
import { Button, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle } from '@mui/material';
import AuthContext from '../../Utils/AuthProvider';


const AdminDeleteUserButton = ({ rowId ,onDelete}) => {
  const [open, setOpen] = useState(false);
  const [error, setError] = useState(null);
  const { user } = useContext(AuthContext);

  const handleOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  const deleteRow = (rowId, e) => {
    if(user.id === rowId){
      alert("現在ログインしているユーザは削除できません");
      return;
    }
    deleteUser(rowId);
    setOpen(false);
  };

  const deleteUser = async (userId) => {
    const apiEndpoint = `http://localhost:8080/admin/users/${userId}`;
    try {
      const response = await fetch(apiEndpoint,{
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
        body: JSON.stringify({})
      });
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      onDelete(userId);
    } catch (error) {
      setError('Failed to delete user');
      console.error('Failed to delete user', error);
    }
  };

  if(error){
    console.error(error);
  }

  return (
    <div>
      <Button variant="contained" color={"primary"} onClick={handleOpen}>
        削除
      </Button>
      <Dialog
        open={open}
        onClose={handleClose}
        aria-labelledby="alert-dialog-title"
        aria-describedby="alert-dialog-description"
      >
        <DialogTitle id="alert-dialog-title">{'確認'}</DialogTitle>
        <DialogContent>
          <DialogContentText id="alert-dialog-description">ID「{rowId}」を本当に削除しますか？</DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleClose} variant="outlined" color="primary" autoFocus>
            やめる
          </Button>
          <Button onClick={(e) => deleteRow(rowId, e)} color="primary">
            削除する
          </Button>
        </DialogActions>
      </Dialog>
    </div>
  );
};

export default AdminDeleteUserButton;
