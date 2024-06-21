import * as React from 'react';
import Box from '@mui/material/Box';
import { DataGrid , useGridApiRef} from '@mui/x-data-grid';
import AdminDeleteButton from './AdminDeleteButton';

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
    field: 'createdAt',
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
    renderCell: (params) => <AdminDeleteButton rowId={ params.id } />
  },
];


export default function AdminPostList({rows}) {
  
  const apiRef = useGridApiRef();

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
