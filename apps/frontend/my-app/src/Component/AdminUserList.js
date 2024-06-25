import * as React from 'react';
import Box from '@mui/material/Box';
import { DataGrid , useGridApiRef} from '@mui/x-data-grid';
import AdminDeleteUserButton from './AdminDeleteUserButton';
import { useEffect, useState } from 'react';




export default function AdminUserList() {
  const columns = [
    { field: 'id', headerName: 'ID', width: 90 },
    {
      field: 'username',
      headerName: 'Username',
      width: 150,
      editable: false,
    },
    {
      field: 'email',
      headerName: 'Email',
      width: 300,
      editable: false,
    },
    {
      field: 'is_admin',
      headerName: 'IsAdmin',
      type: 'boolean',
      width: 110,
      editable: false,
    },
    {
      field: 'registered_at',
      headerName: 'CreatedAt',
      width: 160,
      editable:false,
    },
    {
      field: 'deleteBtn',
      headerName: '削除',
      sortable: false,
      width: 90,
      disableClickEventBubbling: true,
      renderCell: (params) => <AdminDeleteUserButton rowId={ params.id } onDelete={() => handleDelete(params.id)} />
    },
  ];

  const [rows, setRows] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const apiRef = useGridApiRef();

  useEffect(() => {
    const fetchUsers = async () => {
      const apiEndpoint = 'http://localhost:8080/admin/users';
      try {
        const response = await fetch(apiEndpoint,{
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
          }
        });
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await response.json();
        const userList = Object.values(data.data);
        setRows(userList);
      } catch (error) {
        setError('Failed to fetch users');
        console.error('Failed to fetch users', error);
      } finally {
        setLoading(false);
      }
    };

    fetchUsers();
  }, []);

  const handleDelete = (id) => {
    apiRef.current.updateRows([{id:id,_action:'delete'}]); // 行を削除した後にAPIを更新
  };

  if (loading) {
    return <Box sx={{ height: 400, width: '100%' }}>
    <DataGrid
      apiRef={apiRef}
      rows={[]}
      columns={columns}
      initialState={{
        pagination: {
          paginationModel: {
            pageSize: 5,
          },
        },
      }}
      pageSizeOptions={[5]}
      checkboxSelection
      disableRowSelectionOnClick
    />
  </Box>;
  }

  if (error) {
    return <div>{error}</div>;
  }

  return (
    <Box sx={{ height: 400, width: '100%' }}>
      <DataGrid
        apiRef={apiRef}
        rows={rows}
        columns={columns}
        initialState={{
          pagination: {
            paginationModel: {
              pageSize: 5,
            },
          },
        }}
        pageSizeOptions={[5]}
        checkboxSelection
        disableRowSelectionOnClick
      />
    </Box>
  );
}
