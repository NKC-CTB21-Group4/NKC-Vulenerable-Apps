import * as React from 'react';
import Box from '@mui/material/Box';
import { DataGrid , useGridApiRef} from '@mui/x-data-grid';
import AdminDeleteButton from './AdminDeleteButton';

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
    field: 'isAdmin',
    headerName: 'IsAdmin',
    type: 'boolean',
    width: 110,
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


export default function AdminUserList({rows}) {
  
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
