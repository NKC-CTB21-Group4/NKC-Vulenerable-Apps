import * as React from 'react';
import Box from '@mui/material/Box';
import { DataGrid , useGridApiRef} from '@mui/x-data-grid';
import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';




export default function AdminReportDetailList() {
  const columns = [
    { field: 'id', headerName: 'ID', width: 90 },
    {
      field: 'post_id',
      headerName: 'postId',
      width: 90,
      editable: false,
    },
    {
      field: 'user_id',
      headerName: 'user_id',
      width: 90,
      editable: false,
    },
    {
      field: 'reason',
      headerName: 'reason',
      width: 300,
      editable: false,
    },
    {
      field: 'is_true',
      headerName: 'isTrue',
      width: 100,
      editable: false,
    },
    {
      field: 'tags',
      headerName: 'Tags',
      width: 400,
      editable: false,
      renderCell: (params) => (
        <div>
          {params.value.map((tag) => (
            <span key={tag.id} style={{ marginRight: 5 }}>
              {tag.name}
            </span>
          ))}
        </div>
      )
    },
    {
      field: 'reported_at',
      headerName: 'ReportedAt',
      width: 160,
      editable: false,
    },
  ];

  const [rows, setRows] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const {id} = useParams();
  const apiRef = useGridApiRef();

  useEffect(() => {
    const fetchListReportsByPost = async () => {
      const apiEndpoint = `http://localhost:8080/admin/posts/${id}/reports`;
      try {
        const response = await fetch(apiEndpoint,{
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
          },
        });
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const reportsByPost = await response.json();
        setRows(Object.values(reportsByPost.data));
      } catch (error) {
        setError('Failed to fetch reportList');
        console.error('Failed to fetch reportList', error);
      } finally {
        setLoading(false);
      }
    };
    fetchListReportsByPost();
  }, []);



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
