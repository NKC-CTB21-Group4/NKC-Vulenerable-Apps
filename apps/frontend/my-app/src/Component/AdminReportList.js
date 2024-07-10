import * as React from 'react';
import Box from '@mui/material/Box';
import { DataGrid , useGridApiRef} from '@mui/x-data-grid';
import AdminShowReportDetailButton from './AdminShowReportDetailButton';
import { useEffect, useState } from 'react';




export default function AdminReportList() {
  const columns = [
    { field: 'id', headerName: 'ID', width: 90 },
    {
      field: 'postId',
      headerName: 'postId',
      width: 90,
      editable: false,
    },
    {
      field: 'reportCount',
      headerName: 'reportCount',
      width: 90,
      editable: false,
    },
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
      field: 'detailBtn',
      headerName: '通報一覧',
      sortable:false,
      width: 90,
      editable: false,
      disableClickEventBubbling: true,
      renderCell: (params) => <AdminShowReportDetailButton postId={params.row.postId} />
    },
  ];

  const [rows, setRows] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const apiRef = useGridApiRef();

  useEffect(() => {
    const fetchReports = async () => {
      const reportsApiEndpoint = 'http://localhost:8080/admin/reports/posts';
      const postsApiEndpoint = 'http://localhost:8080/admin/posts';
      try {
        const reportResponse = await fetch(reportsApiEndpoint,{
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
          }
        });
        if (!reportResponse.ok) {
          throw new Error('Network response was not ok');
        }
        const reportData = await reportResponse.json();
        const reportsByPostList = Object.values(reportData.data).map((report, index) => ({
          ...report,
          id: index
        }));

        const postResponse = await fetch(postsApiEndpoint,{
          headers:{
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
          }
        });
        if(!postResponse.ok){
          throw new Error('Network response was not ok');
        }
        const postData = await postResponse.json();
        const postList = Object.values(postData.data);
        const conbined = reportsByPostList.map(report => {
          const post = postList.find(post => String(post.id) === report.postId);
          return {
              ...report,
              ...post 
          };
      });
        setRows(conbined);
      } catch (error) {
        setError('Failed to fetch reportList');
        console.error('Failed to fetch reportList', error);
      } finally {
        setLoading(false);
      }
    };

    fetchReports();
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
