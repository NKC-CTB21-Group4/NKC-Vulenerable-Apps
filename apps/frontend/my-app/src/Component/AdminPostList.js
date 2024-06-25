import React, { useEffect, useState } from 'react';
import Box from '@mui/material/Box';
import { DataGrid, useGridApiRef } from '@mui/x-data-grid';
import AdminDeletePostButton from './AdminDeletePostButton';



export default function AdminPostList() {

  const columns = [
    { field: 'id', headerName: 'ID', width: 90 },
    {
      field: 'author_id',
      headerName: 'AuthorId',
      width: 90,
      editable: false,
    },
    {
      field: 'author_name',
      headerName: 'AuthorName',
      width: 150,
      editable: false,
    },
    {
      field: 'content',
      headerName: 'Content',
      width: 300,
      editable: false,
    },
    {
      field: 'created_at',
      headerName: 'CreatedAt',
      width: 160,
      editable: false,
    },
    {
      field: 'deleteBtn',
      headerName: '削除',
      sortable: false,
      width: 90,
      disableClickEventBubbling: true,
      renderCell: (params) => <AdminDeletePostButton rowId={params.id} onDelete={() => handleDelete(params.id)} />
    },
  ];

  const [rows, setRows] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const apiRef = useGridApiRef();

  useEffect(() => {
    const fetchPosts = async () => {
      const apiEndpoint = 'http://localhost:8080/admin/posts';
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
        const postList = Object.values(data.data);
        setRows(postList);
      } catch (error) {
        setError('Failed to fetch posts');
        console.error('Failed to fetch posts', error);
      } finally {
        setLoading(false);
      }
    };

    fetchPosts();
  }, []);

  const handleDelete = (id) => {
    apiRef.current.updateRows([{id:id,_action:'delete'}]); // 行を削除した後にAPIを更新
  };

  if (loading) {
    return  <Box sx={{ height: 400, width: '100%' }}>
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
